<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class CorrectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'work_start' => [
                'required',
            ],
            'work_finish' => [
                'required',
                function ($attribute, $value, $fail) {
                    $workStartTime = Carbon::parse($this->input('work_start'));
                    $workFinishTime = Carbon::parse($value);

                    if ($workStartTime > $workFinishTime) {
                        $fail('出勤時間もしくは退勤時間が不適切な値です');
                    }
                }
            ],
            'break_start.*' => [
                function ($attribute, $value, $fail) {
                    $arrayKey = explode('.', $attribute)[1];

                    $workStartTime = Carbon::parse($this->input('work_start'));
                    $workFinishTime = Carbon::parse($this->input('work_finish'));
                    $breakStart = $this->input('break_start', []);

                    if ($breakStart[$arrayKey]) {
                        $breakStartTime = Carbon::parse($breakStart[$arrayKey]);
                        if ($breakStartTime < $workStartTime || $breakStartTime > $workFinishTime) {
                            $fail(('出勤時間もしくは退勤時間が不適切な値です'));
                        }
                    }
                }
            ],
            'break_finish.*' => [
                function ($attribute, $value, $fail) {
                    $arrayKey = explode('.', $attribute)[1];

                    $workStartTime = Carbon::parse($this->input('work_start'));
                    $workFinishTime = Carbon::parse($this->input('work_finish'));
                    $breakFinish = $this->input('break_finish', []);

                    if ($breakFinish[$arrayKey]) {
                        $breakFinishTime = Carbon::parse($breakFinish[$arrayKey]);
                        if ($breakFinishTime < $workStartTime || $breakFinishTime > $workFinishTime) {
                            $fail(('出勤時間もしくは退勤時間が不適切な値です'));
                        }
                    }
                }
            ],
            'notes' => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'work_start.required' => '出勤時間を入力してください',
            'work_finish.required' => '退勤時間を入力してください',
            'notes.required' => '備考を記入してください',
        ];
    }
}
