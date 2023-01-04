<script>
    import {route} from "@/utils";
    import {inertia} from '@inertiajs/inertia-svelte';

    export let groups = [];
</script>

<ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    {#each groups as group (group.id)}
        <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
            <a href={route('groups.show', group.id)} use:inertia
               class="w-full flex p-6 space-x-6">
                <div class="flex items-center justify-between w-full space-x-3">
                    <h3 class="text-gray-900 text-sm font-medium truncate">{group.title}</h3>

                    <div class="flex items-center text-sm text-gray-400">
                        <div class="inline-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 class="w-4 h-4">
                                <path
                                    d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.667l3-3z"/>
                                <path
                                    d="M11.603 7.963a.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.667l-3 3a2.5 2.5 0 01-3.536-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 105.656 5.656l3-3a4 4 0 00-.225-5.865z"/>
                            </svg>
                            <span class="ml-1 text-gray-600 font-medium">{group.linksCount}</span>
                        </div>

                        <div class="inline-flex ml-3.5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 class="w-4 h-4">
                                <path
                                    d="M5.127 3.502L5.25 3.5h9.5c.041 0 .082 0 .123.002A2.251 2.251 0 0012.75 2h-5.5a2.25 2.25 0 00-2.123 1.502zM1 10.25A2.25 2.25 0 013.25 8h13.5A2.25 2.25 0 0119 10.25v5.5A2.25 2.25 0 0116.75 18H3.25A2.25 2.25 0 011 15.75v-5.5zM3.25 6.5c-.04 0-.082 0-.123.002A2.25 2.25 0 015.25 5h9.5c.98 0 1.814.627 2.123 1.502a3.819 3.819 0 00-.123-.002H3.25z"/>
                            </svg>
                            <span class="ml-1 text-gray-600 font-medium">{group.childGroupsCount}</span>
                        </div>
                    </div>
                </div>
            </a>
        </li>
    {/each}
</ul>
