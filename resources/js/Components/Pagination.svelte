<script>
    import { inertia } from '@inertiajs/inertia-svelte';

    export let links = [];
</script>

{#if links.length > 3}
    <div class={`flex flex-wrap -mb-1 ${$$restProps.class || ''}`}>
        {#each links as link, key (key)}
            {#if link.url === null}
                <div class="mr-1 mb-1 px-4 py-3 text-sm border rounded text-gray-400">
                    {@html link.label}
                </div>
            {:else}
                <a
                    use:inertia
                    href={link.url}
                    class={`mr-1 mb-1 px-4 py-3 text-sm border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500 ${link.active && 'bg-white'}`}>
                    {@html link.label}
                </a>
            {/if}
        {/each}
    </div>
{/if}
