<script>
    import {inertia} from '@inertiajs/svelte';

    export let href;
    export let type = 'button';
</script>

{#if !href}
    <button {...$$restProps} {type}
            class={[$$restProps.class,
            'block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition'].join(' ').trim()}>
        <slot/>
    </button>
{:else}
    <a {...$$restProps} {href} use:inertia
       class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">
        <slot/>
    </a>
{/if}
