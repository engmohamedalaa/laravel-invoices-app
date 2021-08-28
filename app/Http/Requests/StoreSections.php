<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class StoreSections extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      /**
       * By default it returns false, change it to
       * something like this if u are checking authentication
       */
      return Auth::check(); // <------------------

      /**
       * You could also use something more granular, like
       * a policy rule or an admin validation like this:
       * return auth()->user()->isAdmin();
       *
       * Or just return true if you handle the authorization
       * anywhere else:
       * return true;
       */
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      //exit;
      //dd($_POST);
      if(isset($_POST['id'])){
        return [
            'section_name' => 'required|max:255|unique:sections,section_name,'.$_POST['id'],
            //'description' => 'required',
        ];
      }else{
        return [
            'section_name' => 'required|unique:sections|max:255',
            //'description' => 'required',
        ];
      }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
          'section_name.required' => 'يرجي ادخال اسم القسم!',
          'section_name.unique'   => 'عفوا هذا القسم موجود مسبقا!',
          //'description.required'  => 'يرجي ادخال الملاحظات!',
        ];
    }
}
