<?php

namespace App\Services\ServiceProvider;

use App\Models\ServiceProvider\ServiceProviderModel;
use App\Models\ServiceProvider\ServiceProviderDocument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

/**
 * Class ServiceProviderService.
 */
class ServiceProviderService
{
    protected $imagePath = 'photos/service-providers';
    protected $documentPath = 'documents/service-providers';

    public function serviceProvider(): ServiceProviderModel
    {
        return new ServiceProviderModel();
    }

    public function serviceProviderWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->serviceProvider()
            ->with('wallet_balance', 'appointments', 'documents');
    }

    public function serviceProviderApproved(){
        $this->serviceProviderWithRelations()->where('service_providers.status', 'approved');
    }

    public function serviceProviderById($id){
        return $this->serviceProviderWithRelations()->findOrFail($id);
    }

    public function serviceProviderByEmail($email){
        return $this->serviceProviderWithRelations()
            ->where('email', $email)->first();
    }

    public function serviceProviderDocument(): ServiceProviderDocument
    {
        return new ServiceProviderDocument();
    }

    public function serviceProviderDocuments($id){
        return $this->serviceProviderDocument()->where('service_provider_id', $id);
    }

    public function storeServiceProvider($request){

        $input = $request->all();
        $input['photo'] = $this->compressAndUploadImage($request, $this->imagePath, 200, 200);
        $input['photo_path'] = '/'.$this->imagePath.'/';
        return $this->serviceProvider()->create($input);
    }

    public function storeServiceProviderDocument($id, $request){

        $input = $request->all();
        $input['document'] = $this->uploadDocument($request, $this->documentPath);
        $input['document_path'] = '/'.$this->documentPath.'/';
        $input['service_provider_id'] = $id;
        return $this->serviceProviderDocument()->create($input);
    }

    public function publishServiceProvider($id): array
    {
        $provider = $this->serviceProviderById($id);
        return $this->publishItem($provider);
    }

    public function searchServiceProviders($request, $queryBuilder): array
    {
        $input = $request->all();
        // Array for storing search results
        $searchValues = [];

        if(!empty($input['term'])) {
            $searchValues['term'] = $input['term'];
        }

        if(!empty($input['status'])) {
            $searchValues['status'] = $input['status'];
        }

        $providers = $queryBuilder->where(function($query) use ($input){
            // The rest of the queries can come here
            $query->when(!empty($input['term']), static function($q) use($input){
                $q->where('name', 'like' , '%'. $input['term'] .'%')
                    ->orWhere('email', 'like' , '%'. $input['term'] .'%')
                    ->orWhere('mobile', 'like' , '%'. $input['term'] .'%')
                    ->orWhere('services', 'like' , '%'. $input['term'] .'%')
                    ->orWhere('status', 'like' , '%'. $input['term'] .'%');
            });
        })->paginate(15);

        // if result exists return results, else return empty array
        if($providers->total() > 0){
            return [
                'providers' => $providers,
                'total' => $providers->total(),
                'search_values' => $searchValues
            ];
        }
        return [
            'providers' => [],
            'total' => 0,
            'search_values' => $searchValues
        ];
    }

    public function updateServiceProviders($request, $id)
    {
        $input = $request->all();
        $provider = $this->serviceProviderById($id);
        // store previous image in session
        Session::put('previous_image', $provider->photo);
        // Compress and upload image
        $photo = $this->compressAndUploadImage($request, $this->imagePath, 200, 200);
        $input['photo'] = $photo ?: $provider->photo;
        $provider->update($input);
        // Delete previous image if it was updated
        if(Session::get('previous_image') !== $provider->photo){
            $this->deleteFile(Session::get('previous_image'), $this->imagePath);
        }
        return $provider;
    }

    public function deleteServiceProvider($id): void
    {
        $provider = $this->serviceProviderById($id);
        $this->deleteRelations($provider->documents, $this->documentPath);
        $this->deleteRelations($provider->appointments);
        $this->deleteFile($provider->photo, $this->imagePath);
        $provider->delete();
    }

    public function deleteServiceProviderDocument($id): void
    {
        $document = $this->serviceProviderDocument()->findOrFail($id);
        $this->deleteFile($document->document, $this->documentPath);
        $document->delete();
    }

    // Reusable
    protected function publishItem($item): array
    {
        $message = '';
        if($item->status === 'verified'){
            $item->status = 'pending';
            $item_name = $item->name ?? $item->title;
            $message = $item_name.' is pending';
        }else{
            $item->status = 'verified';
            $item_name = $item->name ?? $item->title;
            $message = $item_name.' is verified';
        }
        $item->save();
        return [
            'item' => $item,
            'message' => $message,
        ];
    }

    protected function compressAndUploadImage($request, $path, $width, $height)
    {
        if($file = $request->file('photo')) {
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

    protected function deleteFile($file, $path): void
    {
        if(File::exists(public_path() . '/'.$path.'/' . $file)){
            FILE::delete(public_path() . '/'.$path.'/' . $file);
        }
    }

    protected function deleteRelations($items, $path = null): void
    {
        if($items->count() > 0){
            foreach($items as $item){
                $item_file = $item->photo ?? $item->document ?? $item->image ?? $item->file;
                if($path !== null && !empty($item_file) && File::exists(public_path() . '/'.$path.'/' . $item_file)) {
                    FILE::delete(public_path() . '/'.$path.'/' . $item_file);
                }
                $item->delete();
            }
        }
    }


}
