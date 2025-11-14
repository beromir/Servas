<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.svelte';
    import {page, useForm, router} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import Input from "@/Components/Input.svelte";

    let {user = {}} = $props();

    const profileForm = useForm({
        _method: 'PUT',
        name: user.name,
        email: user.email,
        photo: null,
    });

    let photoPreview = $state(null);
    let photoElement = $state();

    function updateProfileInformation() {
        if (photoElement) {
            $profileForm.photo = photoElement.files[0]
        }

        $profileForm.post(route('user-profile-information.update'), {
            errorBag: 'updateProfileInformation',
            preserveScroll: true,
            onSuccess: () => clearPhotoFileInput(),
        });
    }

    function selectNewPhoto() {
        photoElement.click();
    }

    function updatePhotoPreview() {
        const photo = photoElement.files[0];

        if (!photo) return;

        const reader = new FileReader();

        reader.onload = (e) => {
            photoPreview = e.target.result;
        };

        reader.readAsDataURL(photo);
    }

    function deletePhoto() {
        router.delete(route('current-user-photo.destroy'), {
            preserveScroll: true,
            onSuccess: () => {
                photoPreview = null;
                clearPhotoFileInput();
            },
        });
    }

    function clearPhotoFileInput() {
        if (photoElement?.value) {
            photoElement.value = null;
        }
    }
</script>

<JetFormSection submitted={updateProfileInformation}>
    {#snippet title()}
        Profile Information
    {/snippet}

    {#snippet description()}
        Update your account's profile information and email address.
    {/snippet}

    {#snippet form()}
        <!-- Profile Photo -->
        {#if $page.props.jetstream.managesProfilePhotos}
            <div class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                       bind:this={photoElement}
                       onchange={updatePhotoPreview}>

                <JetLabel id="photo" label="Photo"/>

                <!-- Current Profile Photo -->
                {#if !photoPreview}
                    <div class="mt-2">
                        <img src={user.profile_photo_url} alt={user.name} class="rounded-full size-20 object-cover"/>
                    </div>
                {/if}

                <!-- New Profile Photo Preview -->
                {#if photoPreview}
                    <div class="mt-2">
                        <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                              style={'background-image: url(\'' + photoPreview + '\');'}>
                        </span>
                    </div>
                {/if}

                <JetSecondaryButton class="mt-2 mr-2" type="button" clicked={selectNewPhoto}>
                    Select A New Photo
                </JetSecondaryButton>

                {#if user.profile_photo_path}
                    <JetSecondaryButton type="button" class="mt-2" clicked={deletePhoto}>
                        Remove Photo
                    </JetSecondaryButton>
                {/if}

                {#if $profileForm.errors.photo}
                    <JetInputError message={$profileForm.errors.photo} class="mt-2"/>
                {/if}
            </div>
        {/if}

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="name" label="Name"/>
            <Input id="name" autocomplete="name" bind:value={$profileForm.name} class="mt-1"/>
            <JetInputError message={$profileForm.errors.name} class="mt-2"/>
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="email" label="Email"/>
            <Input type="email" id="email" bind:value={$profileForm.email} class="mt-1"/>
            <JetInputError message={$profileForm.errors.email} class="mt-2"/>
        </div>
    {/snippet}

    {#snippet actions()}
        <JetButton class={$profileForm.processing ? 'opacity-25' : ''} disabled={profileForm.processing} type="submit">
            Save
        </JetButton>
    {/snippet}
</JetFormSection>
