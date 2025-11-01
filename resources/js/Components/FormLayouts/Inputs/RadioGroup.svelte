<script>
    let { label, children } = $props();
</script>

<fieldset>
    <legend class="font-medium text-sm text-gray-700 dark:text-gray-200">{label}</legend>
    <div class="mt-2 space-y-1">
        {@render children?.()}
    </div>
</fieldset>
