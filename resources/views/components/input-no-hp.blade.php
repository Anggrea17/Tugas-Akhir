<div>
    <input type="tel" name="{{ $name ?? 'no_hp' }}" value="{{ old($name ?? 'no_hp', $value ?? '') }}"
        class="border rounded p-2 w-full" maxlength="20" required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
        placeholder="08xxxxxxxxxx">
    @error($name ?? 'no_hp')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
