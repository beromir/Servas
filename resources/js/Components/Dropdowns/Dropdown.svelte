<script>
    import clsx from "clsx";
    import {fade, scale} from "svelte/transition";
    import {clickOutside} from "@/utils/index.js";

    export let showDropdown = false;
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
         transition:fade={{ duration: 100 }}
         class={clsx($$restProps.class, 'absolute left-0 z-10 mt-6 w-44 origin-top-left divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none md:right-0 md:left-[unset] md:origin-top-right md:w-56')}
         role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <slot/>
    </div>
{/if}
