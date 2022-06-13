<?php

declare(strict_types=1);

namespace App\Http\Requests\Permissions;

use App\Services\DTO\ManagePermissionsDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ManagePermissionsRequest.
 */
final class ManagePermissionsRequest extends FormRequest
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
            'modules_ids' => 'nullable|array',
            'modules_ids.*' => 'integer|exists:modules,id',
            'permissions_ids' => 'nullable|array',
            'permissions_ids.*' => 'integer|exists:permissions,id',
        ];
    }

    /**
     * @return ManagePermissionsDTO
     */
    public function getDto(): ManagePermissionsDTO
    {
        $input = $this->toArray();

        $data = [
            'modulesIds' => $input['modules_ids'] ?? [],
            'permissionsIds' => $input['permissions_ids'] ?? [],
        ];

        return new ManagePermissionsDTO(...$data);
    }
}
