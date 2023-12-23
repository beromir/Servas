<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {createEventDispatcher} from 'svelte';

    const dispatch = createEventDispatcher();

    export let title = '';
    export let description = '';
    export let boldText = '';
    export let buttonText = '';
    export let buttonColor = '';

    let showModal = false;

    export const openModal = () => showModal = true;

    function actionButtonClicked() {
        dispatch('actionButtonClicked');
        showModal = false;
    }
</script>

<Modal {title} bind:showModal>
    <p class="text-sm text-gray-500">
        {description}
    </p>
    {#if boldText}
        <div class="mt-1 text-sm text-gray-700 font-medium">
            {boldText}
        </div>
    {/if}

    <svelte:fragment slot="footer">
        <Button on:clicked={actionButtonClicked} title={buttonText} color={buttonColor} focusButton={true}
                class="focus:ring-offset-gray-50"/>
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="hidden focus:ring-offset-gray-50 sm:inline-flex"/>
    </svelte:fragment>
</Modal>
