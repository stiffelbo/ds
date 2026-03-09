<?php

namespace App\Access;

use App\Models\Page;
use App\Models\User;
use App\Models\UserPage;

class AccessResolver
{
    public function resolve(?User $user, string $resourceKey, string $method): AccessContext
    {
        $method = strtoupper(trim($method));
        $resourceKey = trim($resourceKey);

        if (!$user) {
            return $this->denyGuest($resourceKey, $method);
        }

        $page = Page::query()
            ->where('endpoint', $resourceKey)
            ->where('is_active', true)
            ->first();

        if ($user->isAdmin()) {
            return $this->resolveForGlobalAdmin($user, $page, $resourceKey, $method);
        }

        if (!$page) {
            return $this->deny(
                user: $user,
                page: null,
                resourceKey: $resourceKey,
                method: $method,
                denyReason: 'page_not_found'
            );
        }

        $userPage = UserPage::query()
            ->where('user_id', $user->id)
            ->where('page_id', $page->id)
            ->first();

        if (!$userPage) {
            return $this->deny(
                user: $user,
                page: $page,
                resourceKey: $resourceKey,
                method: $method,
                denyReason: 'missing_page_assignment'
            );
        }

        if ($userPage->is_admin) {
            return $this->resolveForPageAdmin($user, $page, $resourceKey, $method);
        }

        return $this->resolveStandard($user, $page, $userPage, $resourceKey, $method);
    }

    protected function resolveForGlobalAdmin(
        User $user,
        ?Page $page,
        string $resourceKey,
        string $method
    ): AccessContext {
        return new AccessContext(
            userId: $user->id,
            pageId: $page?->id,
            resourceKey: $resourceKey,
            method: $method,

            isAuthenticated: true,
            isGlobalAdmin: true,
            isPageAdmin: false,
            hasPageAssignment: true,

            canGet: true,
            canPost: true,
            canPatch: true,
            canDelete: true,

            viewRestrictedFields: [],
            editRestrictedFields: [],

            allowed: true,
            denyReason: null,
        );
    }

    protected function resolveForPageAdmin(
        User $user,
        Page $page,
        string $resourceKey,
        string $method
    ): AccessContext {
        return new AccessContext(
            userId: $user->id,
            pageId: $page->id,
            resourceKey: $resourceKey,
            method: $method,

            isAuthenticated: true,
            isGlobalAdmin: false,
            isPageAdmin: true,
            hasPageAssignment: true,

            canGet: true,
            canPost: true,
            canPatch: true,
            canDelete: true,

            viewRestrictedFields: [],
            editRestrictedFields: [],

            allowed: true,
            denyReason: null,
        );
    }

    protected function resolveStandard(
        User $user,
        Page $page,
        UserPage $userPage,
        string $resourceKey,
        string $method
    ): AccessContext {
        $viewRestrictedFields = $userPage->getViewRestrictedFieldsArray();
        $editRestrictedFields = $userPage->getEditRestrictedFieldsArray();

        $canGet = (bool) $userPage->can_get;
        $canPost = (bool) $userPage->can_post;
        $canPatch = (bool) $userPage->can_patch;
        $canDelete = (bool) $userPage->can_delete;

        $allowed = match ($method) {
            'GET', 'HEAD' => $canGet,
            'POST' => $canPost,
            'PUT', 'PATCH' => $canPatch,
            'DELETE' => $canDelete,
            default => false,
        };

        return new AccessContext(
            userId: $user->id,
            pageId: $page->id,
            resourceKey: $resourceKey,
            method: $method,

            isAuthenticated: true,
            isGlobalAdmin: false,
            isPageAdmin: false,
            hasPageAssignment: true,

            canGet: $canGet,
            canPost: $canPost,
            canPatch: $canPatch,
            canDelete: $canDelete,

            viewRestrictedFields: $viewRestrictedFields,
            editRestrictedFields: $editRestrictedFields,

            allowed: $allowed,
            denyReason: $allowed ? null : 'method_not_allowed',
        );
    }

    protected function denyGuest(string $resourceKey, string $method): AccessContext
    {
        return new AccessContext(
            userId: null,
            pageId: null,
            resourceKey: $resourceKey,
            method: $method,

            isAuthenticated: false,
            isGlobalAdmin: false,
            isPageAdmin: false,
            hasPageAssignment: false,

            canGet: false,
            canPost: false,
            canPatch: false,
            canDelete: false,

            viewRestrictedFields: [],
            editRestrictedFields: [],

            allowed: false,
            denyReason: 'guest',
        );
    }

    protected function deny(
        ?User $user,
        ?Page $page,
        string $resourceKey,
        string $method,
        string $denyReason
    ): AccessContext {
        return new AccessContext(
            userId: $user?->id,
            pageId: $page?->id,
            resourceKey: $resourceKey,
            method: $method,

            isAuthenticated: $user !== null,
            isGlobalAdmin: false,
            isPageAdmin: false,
            hasPageAssignment: false,

            canGet: false,
            canPost: false,
            canPatch: false,
            canDelete: false,

            viewRestrictedFields: [],
            editRestrictedFields: [],

            allowed: false,
            denyReason: $denyReason,
        );
    }
}