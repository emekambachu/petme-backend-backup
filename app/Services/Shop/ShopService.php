<?php

namespace App\Services\Shop;

use App\Models\Shop\ShopItem;
use App\Models\Shop\ShopItemImage;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use Illuminate\Support\Facades\File;

/**
 * Class ShopService.
 */
class ShopService
{
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
        return $this->shopItem()->with('shop_item_images', 'shop_item_orders', 'shop_metric', 'shop_category');
    }

    public function storeShopItem($request){

        $input = $request->all();
        $shopItem = $this->shopItem()->create($input);

        // Store Image
        $this->storeShopItemImage($request, $input, $shopItem);
        return $shopItem;
    }

    public function storeShopItemImage($request, $input, $shopItem): void
    {
        // If image is sent as an array, store as an array
        // else store as single file
        if(!empty($input['images'])){
            if(is_array($input['images'])){
                for($i = 0, $count = count($input['images']); $i < $count; $i++){
                    if(isset($input['images'][$i])){
                        $file = $request->file('images')[$i];
                        $path = '/photos/shop/items';
                        if (!File::exists($path)){
                            File::makeDirectory($path, $mode = 0777, true, true);
                        }
                        $name = time() . $file->getClientOriginalName();
                        //Move image to photos directory
                        $file->move($path, $name);
                    }

                    // Submit images
                    $this->shopItemImage()->create([
                        'shop_item_id' => $shopItem->id,
                        'image' => $name,
                    ]);
                }
            }else{
                $file = $request->file('images');
                $path = '/photos/shop/items';
                if (!File::exists($path)){
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $name = time() . $file->getClientOriginalName();
                //Move image to photos directory
                $file->move($path, $name);
                // Submit images
                $this->shopItemImage()->create([
                    'shop_item_id' => $shopItem->id,
                    'image' => $name,
                ]);
            }
        }

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
        return [
            'shop_item'=>$shopItem,
            'message'=>$message,
        ];
    }

    public function searchShopItems($request): array
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

        $items = $this->shopItemWithRelations()
            ->select(
                'shop_items.id AS shop_item_id',
                'shop_items.name AS shop_item_name',
                'shop_items.description',
                'shop_items.quantity',
                'shop_items.shop_category_id',
                'shop_items.shop_metric_id',
                'shop_items.cost',
                'shop_items.status',
                'shop_items.owner',
                'shop_items.created_at',
                'shop_items.updated_at',
                'shop_categories.id',
                'shop_categories.name',
            )->leftjoin('shop_categories',
                'shop_categories.id', '=', 'shop_items.shop_category_id'
            )->where(function($query) use ($input){
                // The rest of the queries can come here
                $query->when(!empty($input['term']), static function($q) use($input){
                    $q->where('shop_items.name', 'like' , '%'. $input['term'] .'%')
                        ->orWhere('shop_items.description', 'like' , '%'. $input['term'] .'%')
                        ->orWhere('shop_items.cost', 'like' , '%'. $input['term'] .'%')
                        ->orWhere('shop_items.status', 'like' , '%'. $input['term'] .'%')
                        ->orWhere('shop_items.owner', 'like' , '%'. $input['term'] .'%')
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
        if(!empty($image->image) && File::exists(public_path() .'/photos/shop/items/'. $image->image)) {
            FILE::delete(public_path() . '/photos/shop/items/' . $image->image);
        }
        $image->delete();
    }

    public function deleteShopItem($id): void
    {
        $shopItem = $this->shopItemWithRelations()->findOrFail($id);
        // get images from relationship and delete them all
        $shopItemImages = $shopItem->shop_item_images;
        if($shopItemImages->count() > 0){
            foreach($shopItemImages as $image){
                if(!empty($image->image) && File::exists(public_path() .'/photos/shop/items/'. $image->image)) {
                    FILE::delete(public_path() . '/photos/shop/items/' . $image->image);
                }
                $image->delete();
            }
        }
        $shopItem->delete();
    }

}
