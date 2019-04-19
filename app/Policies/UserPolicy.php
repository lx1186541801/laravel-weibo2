<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // 授权策略 更新用户 只能修改自己
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    // 授权策略 删除用户  只能管理员删除 不能删除自己
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }

    // 授权策略 关注用户 不能关注自己
    public function follow(User $currentUser, User $user)
    {
        return $currentUser->id !== $user->id;
    }
}
