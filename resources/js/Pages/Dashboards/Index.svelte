<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import {Link} from "@inertiajs/svelte";
    import {route} from "@/utils";

    $: $title = 'Dashboards';

    export let dashboards = [];
</script>

<Main>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
        {#each dashboards as dashboard}
            <Link href={route('dashboards.show', dashboard.id)}
                  class="flex flex-col justify-between p-4 bg-white shadow rounded-lg">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">{dashboard.title}</h3>
                    {#if dashboard.description}
                        <p class="mt-1 text-sm text-gray-500">{dashboard.description}</p>
                    {/if}
                </div>
                <div class="flex items-center justify-end mt-2 text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd"
                              d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
            </Link>
        {/each}
    </div>
</Main>
