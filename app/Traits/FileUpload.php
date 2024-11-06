<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUpload
{
    public $settings = [
        'attributes'  => ['file'],
        'path'        => 'files/',
        'softdelete'  => true
    ];

    /**
     * add fileSettings method in model (required)
     *
     * @return [
     *      'attributes'  => ['{file_attribute_name}'],
     *      'path'        => '{file_path}',
     *      'softdelete'  => '{boolean}'
     * ];
     */
    abstract protected function fileSettings();

    protected function initializeFileTrait()
    {
        $this->settings = array_merge($this->settings, $this->fileSettings());
    }

    public static function bootFileTrait()
    {
        static::saving(function ($model) {

            foreach ($model->settings['attributes'] as $attribute) {
                if ($model->{$attribute} instanceof UploadedFile) {
                    $file    = $model->{$attribute};
                    $fileName = $model->storeFile($attribute, $file);

                    $model->{$attribute} = $fileName;
                }
            }

        });

        static::deleting(function ($model) {
            $model->deleteAllFiles();
        });
    }

    public function uploadFile($file)
    {
        if ($file instanceof UploadedFile) {
            $fileName = "{$file->hashName()}-{$file->getClientOriginalName()}";
            $file->storeAs($this->settings['path'], $fileName);
            return $fileName;
        }
        return false;
    }

    public function uploadFilePublic($file)
    {
        if ($file instanceof UploadedFile) {
            $fileName = "{$file->hashName()}-{$file->getClientOriginalName()}";
            $file->move(public_path($this->settings['path']), $fileName);
            return $fileName;
        }
        return false;
    }

    public function removeFilePublic($fileName)
    {
        File::delete($fileName);
        return false;
    }

    public function getFileAttribute($value)
    {
        if ($value instanceof UploadedFile) return $value;

        return $value ?
            asset('storage/' . $this->settings['path'] . $value) :
            null;
    }

    protected function storeFile($attribute, $file)
    {
        $this->deleteFile($attribute);

        $fileName = $file->hashName();
        $file->storeAs($this->settings['path'], $fileName);

        return $fileName;
    }

    public function deleteFile($attribute)
    {
        if ($this->settings['softdelete'] === false) {

            Storage::delete($this->settings['path'] . $attribute);
            return true;

        }
        return false;
    }

    protected function deleteAllFiles()
    {
        if ($this->settings['softdelete'] === false){

            foreach ($this->settings['attributes'] as $attribute) {
                $this->deleteFile($attribute);
            }
        }
    }

    public function isFileExists($attribute)
    {
        $file = $this->getRawOriginal($attribute);

        return $file && Storage::exists($this->settings['path'] . $file);
    }
}
