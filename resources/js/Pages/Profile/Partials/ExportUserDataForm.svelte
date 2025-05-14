<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {route} from "@/utils";

    let formSection;
    let form = {
        exportFormat: 'json',
    };
    let csrfToken = '';

    async function exportUserData() {
        await setCsrfToken();

        formSection.submit();
    }

    function setCsrfToken() {
        return axios.get(route('csrf-token'))
            .then(response => csrfToken = response.data);
    }
</script>

<JetFormSection on:submitted={exportUserData} bind:this={formSection} method="post" action={route('export')}>
    <svelte:fragment slot="title">
        Export Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Export your account data as JSON or HTML.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <input type="hidden" name="_token" bind:value={csrfToken}/>

        <div class="col-span-3 space-y-6">
            <fieldset class="dark:text-white">
                <legend>Export format</legend>

                <div>
                    <input type="radio" bind:group={form.exportFormat} id="export-json" name="exportFormat"
                           value="json"/>
                    <label for="export-json">JSON (Servas export)</label>
                </div>

                <div>
                    <input type="radio" bind:group={form.exportFormat} id="export-html" name="exportFormat"
                           value="html"/>
                    <label for="export-html">HTML (Browser bookmarks)</label>
                </div>
            </fieldset>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <JetButton type="submit">
            Export
        </JetButton>
    </svelte:fragment>
</JetFormSection>
