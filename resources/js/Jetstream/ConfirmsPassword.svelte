<script>
    import JetButton from './Button.svelte';
    import JetDialogModal from './DialogModal.svelte';
    import JetInputError from './InputError.svelte';
    import JetSecondaryButton from './SecondaryButton.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import Input from "@/Components/Input.svelte";

    let {
        button = 'Confirm',
        confirmed,
        children
    } = $props();

    let confirmingPassword = $state(false);

    let form = useForm({
        password: '',
        error: '',
    });

    function startConfirmingPassword() {
        axios.get(route('password.confirmation')).then(response => {
            if (response.data.confirmed) {
                confirmed();
            } else {
                confirmingPassword = true;
            }
        })
    }

    function confirmPassword() {
        $form.processing = true;

        axios.post(route('password.confirm'), {
            password: $form.password,
        }).then(() => {
            $form.processing = false;
            closeModal()
            // this.$nextTick(() => this.$emit('confirmed'));
            confirmed();
        }).catch(error => {
            $form.processing = false;
            $form.setError('password', error.response.data.errors.password[0]);
        });
    }

    function closeModal() {
        confirmingPassword = false
        $form.reset();
    }
</script>

<span>
    <span aria-hidden="true" onclick={startConfirmingPassword}>
        {@render children?.()}
    </span>

    <JetDialogModal close={closeModal} show={confirmingPassword}>
        {#snippet title()}
            Confirm Password
        {/snippet}

        {#snippet content()}
            For your security, please confirm your password to continue.

            <div class="mt-4">
                <Input type="password" placeholder="Password" bind:value={$form.password} class="mt-1"/>

                <JetInputError message={$form.errors.password} class="mt-2"/>
                </div>
        {/snippet}

        {#snippet footer()}
            <JetSecondaryButton clicked={closeModal}>
                Cancel
            </JetSecondaryButton>

            <JetButton class={['ml-3', $form.processing ? 'opacity-25' : ''].join(' ').trim()}
                       clicked={confirmPassword} disabled={$form.processing}>
                {button}
            </JetButton>
        {/snippet}>
    </JetDialogModal>
</span>
