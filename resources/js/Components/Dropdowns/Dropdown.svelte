<script>
    import clsx from "clsx";
    import {scale} from "svelte/transition";
    import {clickOutside} from "@/utils/index.js";
    import {cubicIn, cubicOut} from "svelte/easing";

    let { showDropdown = $bindable(false), openingDirection = 'right', children, ...rest } = $props();
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
    <div use:clickOutside onclick_outside={() => showDropdown = false} {...rest}
         in:scale={{ duration: 100, easing: cubicOut, start: 0.95 }}
         out:scale={{ duration: 75, easing: cubicIn, start: 0.95 }}
         class={clsx(rest.class, openingDirection === 'right' ? 'left-0 origin-top-left' : 'right-0 origin-top-right', 'absolute z-10 mt-6 w-44 divide-y divide-gray-200 rounded-md bg-white shadow-lg ring-contrast md:w-56 dark:bg-gray-900 dark:divide-gray-700')}
         role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        {@render children?.()}
    </div>
{/if}
