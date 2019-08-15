<?php

namespace Brackets\Craftable\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait PublishableTrait
{
    /**
     *
     *
     * @return Boolean
     */
    private function hasPublishedTo()
    {
        return in_array('published_to', $this->dates);
    }

    /**
     * Scope a query to only include published models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('published_at', '<=', Carbon::now())
            ->whereNotNull('published_at')
            ->when($this->hasPublishedTo(), function ($query) {
                return $query->where(function ($query2) {
                    $query2->where('published_to', '>=', Carbon::now())
                        ->orWhereNull('published_to');
                });
            });
    }

    /**
     * Scope a query to only include unpublished models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeUnpublished(Builder $query): Builder
    {
        return $query->where('published_at', '>', Carbon::now())->orWhereNull('published_at')
            ->when($this->hasPublishedTo(), function ($query) {
                $query->orWhere('published_to', '<', Carbon::now());
            });
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        if ($this->published_at === null) {
            return false;
        }

        return $this->published_at->lte(Carbon::now()) && ($this->hasPublishedTo() ? ($this->published_to->gte(Carbon::now()) || $this->published_to === null) : true);
    }

    /**
     * @return bool
     */
    public function isUnpublished(): bool
    {
        return !$this->isPublished();
    }

    /**
     * @return bool
     */
    public function publish(): bool
    {
        $data = ['published_at' => Carbon::now()->toDateTimeString()];

        if ($this->hasPublishedTo() && $this->published_to->lte(Carbon::now())) {
            $data['published_to'] = null;
        }

        return $this->update($data);
    }

    /**
     * @return bool
     */
    public function unpublish(): bool
    {
        return $this->update([
            'published_at' => null,
        ]);
    }
}
