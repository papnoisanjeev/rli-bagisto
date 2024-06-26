<!-- Add code into category edit page -->
<x-admin::accordion>
    <x-slot:header>
        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
            @lang('enclaves::app.admin.catalog.category.index.button-setting')
        </p>
    </x-slot:header>
    
    <x-slot:content>
        <div class="mb-4 flex w-full flex-col gap-2">
            <p class="required font-medium text-gray-800 dark:text-white">
                @lang('enclaves::app.admin.catalog.category.index.button.banner')
            </p>

            <p class="text-xs text-gray-500">
                @lang('enclaves::app.admin.catalog.category.index.button.banner-info')
            </p>

            <x-admin::media.images 
                name="community_banner_path" 
                width="220px"
                :uploaded-images="$category->community_banner_path ? [['id' => 'community_banner_path', 'url' => Storage::url($category->community_banner_path)]] : []"
                
            />
        </div>

        <x-admin::form.control-group class="mb-[10px]">
            <x-admin::form.control-group.label class="required">
                @lang('enclaves::app.admin.catalog.category.index.button.text')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="btn_text"
                :value="old('btn_text') ?: $category->btn_text"
                ::rules="{ required: true}"
                :label="trans('enclaves::app.admin.catalog.category.index.button.text')"
                :placeholder="trans('enclaves::app.admin.catalog.category.index.button.text')"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error
                control-name="btn_text"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <x-admin::form.control-group class="mb-[10px]">
            <x-admin::form.control-group.label class="required">
                @lang('enclaves::app.admin.catalog.category.index.button.color')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="color"
                name="btn_color"
                :value="old('btn_color') ?: $category->btn_color"
                ::rules="{ required: true, regex: /^#([A-Za-z0-9]+)$/ }"
                :label="trans('enclaves::app.admin.catalog.category.index.button.color')"
                :placeholder="trans('enclaves::app.admin.catalog.category.index.button.color')"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error
                control-name="btn_color"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <x-admin::form.control-group class="mb-[10px]">
            <x-admin::form.control-group.label class="required">
                @lang('enclaves::app.admin.catalog.category.index.button.border-color')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="color"
                name="btn_border_color"
                :value="old('btn_border_color') ?: $category->btn_border_color"
                ::rules="{ required: true, regex: /^#([A-Za-z0-9]+)$/ }"
                :label="trans('enclaves::app.admin.catalog.category.index.button.border-color')"
                :placeholder="trans('enclaves::app.admin.catalog.category.index.button.border-color')"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error
                control-name="btn_border_color"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>
        
        <x-admin::form.control-group class="mb-[10px]">
            <x-admin::form.control-group.label class="required">
                @lang('enclaves::app.admin.catalog.category.index.button.background-color')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="color"
                name="btn_background_color"
                :value="old('background_color') ?: $category->btn_background_color"
                ::rules="{ required: true, regex: /^#([A-Za-z0-9]+)$/ }"
                :label="trans('enclaves::app.admin.catalog.category.index.button.background-color')"
                :placeholder="trans('enclaves::app.admin.catalog.category.index.button.background-color')"
            />

            <x-admin::form.control-group.error
                control-name="btn_background_color"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <x-admin::form.control-group class="mb-[10px]">
            <x-admin::form.control-group.label class="required">
                @lang('enclaves::app.admin.catalog.category.index.button.sort')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="number"
                name="sort"
                :value="old('sort') ?: $category->sort"
                ::rules="{required: true}"
                :label="trans('enclaves::app.admin.catalog.category.index.button.sort')"
                :placeholder="trans('enclaves::app.admin.catalog.category.index.button.sort')"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error
                control-name="sort"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <x-admin::form.control-group class="mb-[10px]">
            <x-admin::form.control-group.label>
                @lang('enclaves::app.admin.catalog.category.index.button.status')
            </x-admin::form.control-group.label>
            
            @php $selectedOption = old('communities_status') ?: $category->communities_status @endphp

            <x-admin::form.control-group.control
                type="switch"
                name="switch_status"
                value="1"
                :label="trans('enclaves::app.admin.catalog.category.index.button.status')"
                :checked="(bool) $selectedOption"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error
                control-name="switch_status"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>
        
        <input type="hidden" name="communities_status" value="{{ $category->communities_status }}">
    </x-slot:content>
</x-admin::accordion>