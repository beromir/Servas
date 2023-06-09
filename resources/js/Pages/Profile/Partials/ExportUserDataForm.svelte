<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {route} from "@/utils";
    import axios from "axios";
    import SuccessMessage from "@/Jetstream/SuccessMessage.svelte";

    let exportOptions = [
        'links',
        'groups',
        'tags',
    ];

    let selectedExportOptions = [];

    let formSection;
    let csrfToken = '';

    function setCsrfToken() {
        return axios.get(route('csrf-token'))
            .then(response => csrfToken = response.data);
    }

    async function getUserDataExport() {
        await setCsrfToken();

        formSection.submit();
    }
</script>

<JetFormSection on:submitted={getUserDataExport} bind:this={formSection} method="post"
                action={route('export')}>
    <svelte:fragment slot="title">
        Export Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Export your account data as a JSON file.
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
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <JetButton type="submit" disabled={!selectedExportOptions.length}>
            Export
        </JetButton>
    </svelte:fragment>
</JetFormSection>
