<?php

namespace App\Services\Shop;

use App\Models\Shop\ShopItem;
use App\Models\Shop\ShopItemImage;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use Illuminate\Support\Facades\File;

/**
 * Class ShopService.
 */
class ShopService
{
    protected $imagePath = 'photos/shop/items';

    public function shopItem (): ShopItem
    {
        return new ShopItem();
    }

    public function shopItemImage (): ShopItemImage
    {
        return new ShopItemImage();
    }

    public function shopItemWithRelations (): \Illuminate\Database\Eloquent\Builder
    {
        return $this->shopItem()->with(
            'images',
            'orders',
            'metric',
            'category',
            'discount',
        );
    }

    public function shopItemPublished(){
        return $this->shopItemWithRelations()->where('status', 'published');
    }

    public function shopItemPublishedJoins(){
        return $this->shopItemWithRelations()->where('shop_items.status', 'published');
    }

    public function shopItemById($id){
        return $this->shopItemWithRelations()->findOrFail($id);
    }

    public function storeShopItem($request){
        $input = $request->all();
        $shopItem = $this->shopItem()->create($input);
        // Store Image
        $this->storeShopItemImage($request, $shopItem);
        return $shopItem;
    }

    public function storeShopItemImage($request, $shopItem): void
    {
        $name = $this->compressAndUploadImage($request, $this->imagePath, 200, 200);
        // Submit images
        $this->shopItemImage()->create([
            'shop_item_id' => $shopItem->id,
            'image' => $name,
            'image_path' => @config('app.url').$this->imagePath.'/',
        ]);
    }

    public function publishShopItem($id): array
    {
        $shopItem = $this->shopItem()->findOrFail($id);
        $message = '';
        if($shopItem->status === 'published'){
            $shopItem->status = 'pending';
            $message = $shopItem->name.' is now hidden';
        }else{
            $shopItem->status = 'published';
            $message = $shopItem->name.' is now published';
        }
        $shopItem->save();
        return [
            'shop_item'=>$shopItem,
            'message'=>$message,
        ];
    }

    public function searchShopItems($request, $queryBuilder): array
    {
        $input = $request->all();
        $request->session()->forget(['search_inputs']);

        // Create empty array for search values session
        // Add all input to search inputs session, can be easily passed to export functionality
        $request->session()->put('search_inputs', $input);
        $searchValues = [];

        if(!empty($input['term'])) {
            $searchValues['term'] = $input['term'];
        }

        $items = $queryBuilder->select(
                'shop_items.id AS shop_item_id',
                'shop_items.name AS shop_item_name',
                'shop_items.*',
                'shop_categories.id',
                'shop_categories.name',
            )->leftjoin('shop_categories',
                'shop_categories.id', '=', 'shop_items.shop_category_id'
            )->where(function($query) use ($input){
                // The rest of the queries can come here
                $query->when(!empty($input['term']), static function($q) use($input){
                    $q->where('shop_items.name', 'like' , '%'. $input['term'] .'%')
                        ->orWhere('shop_items.description', 'like' , '%'. $input['term'] .'%')
                        ->orWhere('shop_categories.name', 'like' , '%'. $input['term'] .'%');
                });
            })->paginate(15);

        // if result exists return results, else return empty array
        if($items->total() > 0){
            return [
                'shop_items' => $items,
                'total' => $items->total(),
                'search_values' => $searchValues
            ];
        }

        return [
            'shop_items' => [],
            'total' => 0,
            'search_values' => $searchValues
        ];
    }

    public function updateShopItem($request, $id)
    {
        $input = $request->all();
        $shopItem = $this->shopItem()->findOrFail($id);
        $shopItem->update($input);
        return $shopItem;
    }

    public function deleteShopItemImage($id): void
    {
        $image = $this->shopItemImage()->findOrFail($id);
        $this->deleteFile($image->image, $this->imagePath);
        $image->delete();
    }

    public function deleteShopItem($id): void
    {
        $shopItem = $this->shopItemWithRelations()->findOrFail($id);
        // Delete all relationships from shop items
        $this->deleteRelations($shopItem->shop_item_images);
        $shopItem->delete();
    }


    // Reusable
    protected function publishItem($item): array
    {
        $message = '';
        if($item->status === 'published'){
            $item->status = 'pending';
            $item_name = $item->name ?? $item->title;
            $message = $item_name.' is hidden';
        }else{
            $item->status = 'published';
            $item_name = $item->name ?? $item->title;
            $message = $item_name.' is published';
        }
        $item->save();

        return [
            'item' => $item,
            'message' => $message,
        ];
    }

    protected function compressAndUploadImage($request, $path, $width, $height)
    {
        if($file = $request->file('images')) {
            $name = time() . $file->getClientOriginalName();
            // create path to directory
            if (!File::exists($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $background = Image::canvas($width, $height);
            // start image conversion (Must install Image Intervention Package first)
            $convert_image = Image::make($file->path());
            // resize image and save to converted path
            // resize and fit width
            $convert_image->resize($width, $height, static function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            // insert image to canvas
            $background->insert($convert_image, 'center');
            $background->save($path.'/'.$name);
            // Return full image upload path
            return $name;
        }
        return false;
    }

    protected function uploadDocument($request, $path)
    {
        if($file = $request->file('document')) {
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path($path), $name);
            return $name;
        }
        return false;
    }

    protected function deleteFile($fileName, $filePath): void
    {
        if(File::exists(public_path() . '/'.$filePath.'/' . $fileName)){
            FILE::delete(public_path() . '/'.$filePath.'/' . $fileName);
        }
    }

    protected function deleteRelations($items, $filePath = null): void
    {
        if($items->count() > 0){
            foreach($items as $item){
                $fileName = $item->photo ?? $item->document ?? $item->image ?? $item->file;
                if($filePath !== null && !empty($fileName) && File::exists(public_path() . '/'.$filePath.'/' . $fileName)) {
                    FILE::delete(public_path() . '/'.$filePath.'/' . $fileName);
                }
                $item->delete();
            }
        }
    }
}
