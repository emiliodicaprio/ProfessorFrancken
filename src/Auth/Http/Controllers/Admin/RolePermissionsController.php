<?php

declare(strict_types=1);

namespace Francken\Auth\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RolePermissionsController
{
    public function store(Role $role, Permission $permission, Request $request) : RedirectResponse
    {
        $role->givePermissionTo($request->get('permission_id'));
        return redirect()->back();
    }

    public function remove(Role $role, Permission $permission) : RedirectResponse
    {
        $role->revokePermissionTo($permission);
        return redirect()->back();
    }
}
