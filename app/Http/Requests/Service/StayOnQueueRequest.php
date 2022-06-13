<?php

declare(strict_types=1);

namespace App\Http\Requests\Service;

use App\Exceptions\ReturnResponseException;
use App\Services\DTO\StayOnQueueDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StayOnQueueRequest.
 */
final class StayOnQueueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'till_date' => 'required|date',
            'material_type' => 'required|string',
            'id' => 'required|integer',
        ];
    }

    /**
     * @return StayOnQueueDTO
     * @throws ReturnResponseException
     */
    public function getDto(): StayOnQueueDTO
    {
        return StayOnQueueDTO::fromArray(array_merge([
            'user_cid' => $this->user()->user_cid,
        ], $this->toArray()));
    }
}
