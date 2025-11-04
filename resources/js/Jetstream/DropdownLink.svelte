<script>
    import {inertia} from '@inertiajs/svelte';

    let { href, type = 'button', children, ...rest } = $props();
</script>

{#if !href}
    <button {...rest} {type}
            class={[rest.class,
            'block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 transition'].join(' ').trim()}>
        {@render children?.()}
    </button>
{:else}
    <a {...rest} {href} use:inertia
       class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 transition">
        {@render children?.()}
    </a>
{/if}
