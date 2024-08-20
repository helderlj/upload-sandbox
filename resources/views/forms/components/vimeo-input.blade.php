<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }"
         class="bg-gray-100 px-6 py-4 mt-4 rounded border-2 cursor-pointer text-center">
        <label for="video" class="w-full bg-gray-300 ">Clique aqui para adicionar</label>
        <input type="file" id="video" name="video" class="hidden"/>
    </div>
</x-dynamic-component>
