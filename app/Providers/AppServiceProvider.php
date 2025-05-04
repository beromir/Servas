<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Spatie\Tags\Tag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('search', function ($field, $string) {
            return $string ? $this->where($field, 'like', '%' . $string . '%') : $this;
        });

        Builder::macro('additionalSearch', function ($field, $string) {
            return $string ? $this->orWhere($field, 'like', '%' . $string . '%') : $this;
        });

        Builder::macro('filterByTags', function (array $tags) {
            if (empty($tags)) {
                return $this;
            }

            $locale = app()->getLocale();

            $tags = array_filter($tags);

            if (empty($tags)) {
                return $this;
            }

            // Filter tags by current user
            $tagModels = Tag::filterByCurrentUser()
                ->where(function ($query) use ($tags, $locale) {
                    foreach ($tags as $tag) {
                        $query->orWhere(function ($subQuery) use ($tag, $locale) {
                            $subQuery->where("name->{$locale}", $tag)
                                ->orWhere("slug->{$locale}", $tag);
                        });
                    }
                })
                ->get();

            if ($tagModels->isEmpty()) {
                return $this;
            }

            return $this->withAllTags($tagModels);
        });

        Builder::macro('filterByCurrentUser', function (string $userColumn = 'user_id') {
            return $this->where($userColumn, Auth::id());
        });

        Builder::macro('filterLinks', function (string $searchString, array $filteredTags = [], bool $showUntaggedOnly = false) {
            return $this->where(function ($query) use ($searchString) {
                $query->search('title', $searchString)
                    ->additionalSearch('link', $searchString);
            })
                ->when($showUntaggedOnly, fn($query) => $query->whereDoesntHave('tags'))
                ->when(!$showUntaggedOnly, fn($query) => $query->filterByTags($filteredTags))
                ->orderBy('created_at', 'desc')
                ->paginate(20)
                ->withQueryString();
        });
    }
}
