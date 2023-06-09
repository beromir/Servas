<script>
    export let show = false;
    export let message = '';
</script>

{#if show}
    <div class="mr-4 text-sm text-green-700">
        {message}
    </div>
{/if}
