<?php

declare(strict_types=1);

namespace App\Http\Requests\Service;

use App\Services\DTO\CancelReservationDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CancelReservationRequest.
 */
final class CancelReservationRequest extends FormRequest
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
            'material_id' => 'required|integer',
        ];
    }

    /**
     * @return CancelReservationDTO
     */
    public function getDto(): CancelReservationDTO
    {
        $materialId = $this->input('material_id');
        $userCid = $this->user()->user_cid;

        return new CancelReservationDTO(
            materialId: (int) $materialId,
            userCid: $userCid
        );
    }
}
