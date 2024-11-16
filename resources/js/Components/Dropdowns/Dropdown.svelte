<script>
    import clsx from "clsx";
    import {scale} from "svelte/transition";
    import {clickOutside} from "@/utils/index.js";
    import {cubicIn, cubicOut} from "svelte/easing";

    export let showDropdown = false;
    export let openingDirection = 'right';
</script>

<!--
    Dropdown menu, show/hide based on menu state.

    Entering: "transition ease-out duration-100"
      From: "transform opacity-0 scale-95"
      To: "transform opacity-100 scale-100"
    Leaving: "transition ease-in duration-75"
      From: "transform opacity-100 scale-100"
      To: "transform opacity-0 scale-95"
  -->
{#if showDropdown}
    <div use:clickOutside on:click_outside={() => showDropdown = false} {...$$restProps}
         in:scale={{ duration: 100, easing: cubicOut, start: 0.95 }}
         out:scale={{ duration: 75, easing: cubicIn, start: 0.95 }}
         class={clsx($$restProps.class, openingDirection === 'right' ? 'left-0 origin-top-left' : 'right-0 origin-top-right', 'absolute z-10 mt-6 w-44 divide-y divide-gray-200 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none md:w-56 dark:bg-gray-900 dark:ring-contrast dark:divide-gray-700')}
         role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <slot/>
    </div>
{/if}
