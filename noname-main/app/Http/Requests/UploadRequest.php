<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:35',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:a,m,d',
            'file' => 'required|file',
        ];

        switch ($this->input('type')) {
            case 'a':
                $rules['file'] = 'mimes:mp3,wav,ogg|max:10240|dimensions:max_duration:300';
                break;
            case 'm':
                $rules['file'] = 'mimes:rbxm,rbxmx|max:10240'; 
                break;
            case 'd':
                $rules['file'] = 'image|mimes:jpeg,png,jpg|max:5120';
                break;
        }

        return $rules;
    }
}
