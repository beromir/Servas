<script>
    import {route} from "@/utils";
    import {inertia} from '@inertiajs/inertia-svelte';

    export let groups = [];
</script>

<ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    {#each groups as group (group.id)}
        <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
            <a href={route('groups.show', group.id)} use:inertia
               class="w-full flex items-center justify-between p-6 space-x-6">
                <div class="flex-1 truncate">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-gray-900 text-sm font-medium truncate">{group.title}</h3>
                    </div>
                </div>
            </a>
        </li>
    {/each}
</ul>
