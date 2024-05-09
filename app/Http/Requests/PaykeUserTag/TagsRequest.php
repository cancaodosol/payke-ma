<?php

namespace App\Http\Requests\PaykeUserTag;

use App\Models\PaykeUserTag;
use Illuminate\Foundation\Http\FormRequest;

class TagsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [];
    }

    public function to_payke_user_tags()
    {
        $tags = [];
        $tags_count = $this->input("tags_count");
        for($i = 1; $i <= $tags_count; $i++){
            $tag = new PaykeUserTag();
            $tag->id = $this->input('tag_id_'.$i);
            $tag->name = $this->input('name_'.$i);
            $tag->order_no = $this->input('order_no_'.$i) ?? 100000000;
            $tag->color = $this->input('color_'.$i);
            $tag->is_hidden = $this->input('is_hidden_'.$i) != null;
            $tags[] = $tag;
        }

        if($this->input('name_new')){
            $tag = new PaykeUserTag();
            $tag->name = $this->input('name_new');
            $tag->order_no = $this->input('order_no_new') ?? 100000000;
            $tag->color = $this->input('color_new');
            $tag->is_hidden = $this->input('is_hidden_new') != null;
            $tags[] = $tag;
        }

        return $tags;
    }
}
