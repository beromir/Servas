<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {route} from "@/utils";
    import axios from "axios";

    let exportOptions = [
        'links',
        'groups',
        'tags',
    ];

    let selectedExportOptions = [];

    let formSection;
    let csrfToken = '';

    function setCsrfToken() {
        return axios.get(route('csrfToken'))
            .then(response => csrfToken = response.data);
    }

    async function getUserDataExport() {
        if (!selectedExportOptions.length) {
            return;
        }

        await setCsrfToken();

        formSection.submit();
    }
</script>

<JetFormSection on:submitted={getUserDataExport} bind:this={formSection} method="post"
                action={route('export')}>
    <svelte:fragment slot="title">
        Export User Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Export your data as a JSON file.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <input type="hidden" name="_token" bind:value={csrfToken}/>

        <div class="col-span-3">
            {#each exportOptions as option}
                <label class="flex items-center">
                    <input type="checkbox" name="exportOptions[]" value={option} bind:group={selectedExportOptions}
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"/>
                    <span class="ml-2 text-sm text-gray-600 capitalize">{option}</span>
                </label>
            {/each}

            {#if !selectedExportOptions.length}
                <div class="text-red-500">Please select a least one export option.</div>
            {/if}
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <JetButton type="submit">
            Export
        </JetButton>
    </svelte:fragment>
</JetFormSection>
