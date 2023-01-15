<script context="module">
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.svelte';
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.svelte';
    import JetButton from '@/Jetstream/Button.svelte';
    import {useForm, inertia} from "@inertiajs/svelte";
    import {route} from "@/utils";

    export let status = '';

    let form = useForm({});

    $title = 'Email Verification';

    $: verificationLinkSent = status === 'verification-link-sent';

    function submit() {
        $form.post(route('verification.send'));
    }
</script>

<JetAuthenticationCard>
    <svelte:fragment slot="logo">
        <JetAuthenticationCardLogo/>
    </svelte:fragment>

    <div class="mb-4 text-sm text-gray-600">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we
        just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    {#if verificationLinkSent}
        <div class="mb-4 font-medium text-sm text-green-600">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    {/if}

    <form on:submit|preventDefault={submit}>
        <div class="mt-4 flex items-center justify-between">
            <JetButton class={$form.processing ? 'opacity-25' : ''} disabled={$form.processing} type="submit">
                Resend Verification Email
            </JetButton>

            <button use:inertia={{ href: route('logout'), method: 'post' }} type="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900">
                Log Out
            </button>
        </div>
    </form>
</JetAuthenticationCard>
