<?php

namespace App\Access;

class AccessContext
{
    public function __construct(
        public readonly ?int $userId,
        public readonly ?int $pageId,
        public readonly string $resourceKey,
        public readonly string $method,

        public readonly bool $isAuthenticated,
        public readonly bool $isGlobalAdmin,
        public readonly bool $isPageAdmin,
        public readonly bool $hasPageAssignment,

        public readonly bool $canGet,
        public readonly bool $canPost,
        public readonly bool $canPatch,
        public readonly bool $canDelete,

        public readonly array $viewRestrictedFields,
        public readonly array $editRestrictedFields,

        public readonly bool $allowed,
        public readonly ?string $denyReason = null,
    ) {}

    public function isAllowed(): bool
    {
        return $this->allowed;
    }

    public function canAccessMethod(?string $method = null): bool
    {
        $method = strtoupper($method ?: $this->method);

        if ($this->isGlobalAdmin || $this->isPageAdmin) {
            return true;
        }

        return match ($method) {
            'GET', 'HEAD' => $this->canGet,
            'POST' => $this->canPost,
            'PUT', 'PATCH' => $this->canPatch,
            'DELETE' => $this->canDelete,
            default => false,
        };
    }

    public function canViewField(string $field): bool
    {
        if ($this->isGlobalAdmin || $this->isPageAdmin) {
            return true;
        }

        if (!$this->canGet) {
            return false;
        }

        return !in_array($field, $this->viewRestrictedFields, true);
    }

    public function canEditField(string $field): bool
    {
        if ($this->isGlobalAdmin || $this->isPageAdmin) {
            return true;
        }

        if (!$this->canPost && !$this->canPatch) {
            return false;
        }

        return !in_array($field, $this->editRestrictedFields, true);
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'page_id' => $this->pageId,
            'resource_key' => $this->resourceKey,
            'method' => $this->method,

            'is_authenticated' => $this->isAuthenticated,
            'is_global_admin' => $this->isGlobalAdmin,
            'is_page_admin' => $this->isPageAdmin,
            'has_page_assignment' => $this->hasPageAssignment,

            'can_get' => $this->canGet,
            'can_post' => $this->canPost,
            'can_patch' => $this->canPatch,
            'can_delete' => $this->canDelete,

            'allowed' => $this->allowed,
            'is_method_allowed' => $this->canAccessMethod(),
            'deny_reason' => $this->denyReason,

            'view_restricted_fields' => array_values($this->viewRestrictedFields),
            'edit_restricted_fields' => array_values($this->editRestrictedFields),
        ];
    }
}