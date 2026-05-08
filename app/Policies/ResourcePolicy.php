<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    /**
     * Determina si l'usuari pot visualitzar el recurs.
     *
     * @param User|null $user
     * @param Resource $resource
     * @return bool
     */
    public function view(?User $user, Resource $resource): bool
    {
        // Si l'usuari no està autenticat, no pot veure
        if (!$user) {
            return false;
        }

        $role = strtoupper((string) ($user->role ?? ''));

        // Els ADMIN sempre poden veure qualsevol recurs
        if ($role === 'ADMIN') {
            return true;
        }

        // Els recursos d'usuaris privats no es poden visualitzar
        if ($resource->user && $resource->user->is_private) {
            return false;
        }

        // Els estudiants no poden veure exàmens
        if (strtoupper((string) ($user->role ?? '')) === 'STUDENT') {
            if (strtolower((string) ($resource->type ?? '')) === 'exam') {
                return false;
            }
        }

        return true;
    }

    /**
     * Determina si l'usuari pot crear un recurs.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        $role = strtoupper((string) ($user->role ?? ''));
        
        // Els ADMIN sempre poden crear
        if ($role === 'ADMIN') {
            return true;
        }

        // Els TEACHER només si el seu status és ACTIVE o VERIFIED
        if ($role === 'TEACHER') {
            $teacherStatus = strtoupper((string) ($user->teacher_status ?? ''));
            return in_array($teacherStatus, ['ACTIVE', 'VERIFIED'], true);
        }

        return false;
    }

    /**
     * Determina si l'usuari pot actualitzar el recurs.
     *
     * @param User $user
     * @param Resource $resource
     * @return bool
     */
    public function update(User $user, Resource $resource): bool
    {
        $role = strtoupper((string) ($user->role ?? ''));

        // El propietari sempre pot actualitzar
        if ($user->id === $resource->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determina si l'usuari pot eliminar el recurs.
     *
     * @param User $user
     * @param Resource $resource
     * @return bool
     */
    public function delete(User $user, Resource $resource): bool
    {
        $role = strtoupper((string) ($user->role ?? ''));

        // El propietari sempre pot eliminar
        if ($user->id === $resource->user_id) {
            return true;
        }

        // Els ADMIN sempre poden eliminar
        if ($role === 'ADMIN') {
            return true;
        }

        return false;
    }
}
