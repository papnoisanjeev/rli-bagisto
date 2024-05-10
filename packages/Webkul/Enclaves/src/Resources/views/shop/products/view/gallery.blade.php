<v-product-gallery ref="gallery">
    <x-shop::shimmer.products.gallery/>
</v-product-gallery>

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-gallery-template">
        <div class="gap-[20px] max-1280:flex-wrap">
            <div class="w-full">
                <!-- Media shimmer Effect -->
                <div class="max-h-[700px]" v-show="isMediaLoading">
                    <div class="shimmer min-h-[700px] rounded-[12px] bg-[#E9E9E9]"></div>
                </div>

                <img
                    v-show="! isMediaLoading"
                    :src="baseFile.path" 
                    v-if="baseFile.type == 'image'"
                    class="h-[700px] w-full cursor-pointer rounded-[10px]"
                    alt="@lang('shop::app.products.view.gallery.product-image')"
                    @load="onMediaLoad()"
                />
            </div>
            
            <div class="relative flex flex-wrap gap-[16px]" v-if="isMobile">
                <template v-for="(image, index) in media.images">
                    <div v-if="index < 3" 
                        :class="`${index == `2` ? 'relative' : ''}`">
                        <x-shop::media.images.lazy
                            alt="@lang('shop::app.products.view.gallery.thumbnail-image')" 
                            v-if="index < 3"
                            ::class="`min-w-[100px] max-h-[100px] rounded-xl border transparent cursor-pointer ${activeIndex === `image_${index}` ? 'border border-navyBlue pointer-events-none' : 'border-white'}`"
                            ::src="image.small_image_url"
                            @click="change(image, `image_${index}`)"
                        >
                        </x-shop::media.images.lazy>

                        <p
                            v-if="index == 2 && ((media.images.length - 3) != 0)"
                            class="absolute bottom-[10px] right-2 cursor-pointer bg-black p-1 text-white" 
                            v-text="'+' + (media.images.length - 3)"
                            @click="productSliderModel()"
                        ></p>
                    </div>
                </template>
            </div>

            <div class="mt-[10px] flex w-[100px] gap-[10px]" v-else>
                <template v-for="(image, index) in media.images">

                    <div v-if="index < 7" 
                        :class="`${index == `6` ? 'relative' : ''}`">

                        <img 
                            :src="image.small_image_url"  
                            :class="`min-w-[100px] max-h-[100px] rounded-xl border transparent cursor-pointer ${activeIndex === `image_${index}` ? 'border border-navyBlue pointer-events-none' : 'border-white'}`"  
                            @click="change(image, `image_${index}`)"
                            alt="{{ trans('shop::app.products.view.gallery.thumbnail-image') }}"
                        >

                        <p
                            v-if="index == 6 && ((media.images.length - 7) != 0)"
                            class="absolute bottom-[30px] right-2 cursor-pointer bg-black p-1 text-white" 
                            v-text="'+' + (media.images.length - 7)"
                            @click="productSliderModel()"
                        ></p>
                    </div>
                </template>
            </div>
        </div>

        <!-- Product slider Image with shimmer -->
        <div class="scrollbar-hide flex gap-[30px] overflow-auto max-1180:hidden 1180:hidden">
            <x-shop::media.images.lazy
                ::src="baseFile.path"
                v-if="baseFile.type == 'image'"
                class="h-[700px] w-[700px] rounded-[5px]"
                alt="@lang('shop::app.products.view.gallery.product-image')"
                @load="onMediaLoad()"
            >
            </x-shop::media.images.lazy>
        </div>


        <div class="mt-[40px] flex flex-wrap gap-[30px] max-sm:gap-[30px]">
            <div class="flex gap-[10px]" v-for="option in options">
                <span class="icon h-[24px] w-[24px] bg-red-700"></span>

                <div class="grid gap-[12px]">
                    <p class="text-[15px] leading-4 text-[#989898]" v-text="option.label"></p>
                    
                    <p class="text-[18px] leading-4"  v-text="option.value"></p>
                </div>
            </div>
        </div>

        <x-shop::modal.image-slider ref="imageSliderModel">
            <!-- Modal Content Id -->
            <x-slot:content>
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <div class="relative m-auto">
                        <div
                            v-for="(image, index) in media.images"
                            class="fade p-4"
                            ref="slides"
                            :key="index"
                            aria-label="Image Slide"
                        >
                            <img
                                :src="image.large_image_url"
                                class="h-[480px] w-full cursor-pointer rounded-[5px]"
                                alt="@lang('shop::app.products.view.gallery.product-image')"
                            />
                        </div>

                        <span
                            class="icon-arrow-left absolute left-[10px] top-1/2 -mt-[22px] w-auto cursor-pointer rounded-full bg-[rgba(0,0,0,0.8)] p-[12px] text-[24px] font-bold text-white opacity-30 transition-all hover:opacity-100"
                            v-if="media.images?.length >= 2"
                            @click="navigate(currentIndex -= 1)"
                        >
                        </span>

                        <span
                            class="icon-arrow-right absolute right-[10px] top-1/2 -mt-[22px] w-auto cursor-pointer rounded-full bg-[rgba(0,0,0,0.8)] p-[12px] text-[24px] font-bold text-white opacity-30 transition-all hover:opacity-100"
                            v-if="media.images?.length >= 2"
                            @click="navigate(currentIndex += 1)"
                        >
                        </span>
                    </div>
                </x-shop::form>
            </x-slot:content>
        </x-shop::modal.image-slider>
    </script>

    <script type="module">
        app.component('v-product-gallery', {
            template: '#v-product-gallery-template',

            data() {
                return {
                    isMobile: window.innerWidth <= 768,

                    isMediaLoading: true,

                    currentIndex: 1,

                    options: [
                        {
                            label: "1st Floor",
                            value: null,
                        }, {
                            label: "End Unit",
                            value: null,
                        }, {
                            label: "Unit Type",
                            value: null,
                        },
                    ],
                   
                    media: {
                        images: @json(product_image()->getGalleryImages($product)),

                        videos: @json(product_video()->getVideos($product)),
                    },

                    baseFile: {
                        type: '',

                        path: ''
                    },

                    activeIndex: 0,

                    containerOffset: 110,

                    componentRefresh: 1,
                }
            },

            watch: {
                'media.images': {
                    deep: true,

                    handler(newImages, oldImages) {
                        let selectedImage = newImages?.[this.activeIndex.split('_').pop()];

                        if (JSON.stringify(newImages) !== JSON.stringify(oldImages) && selectedImage?.large_image_url) {
                            this.baseFile.path = selectedImage.large_image_url;
                        }
                    },
                },
            },

            updated() {
                ++this.componentRefresh;
            },
            
            mounted() {
                this.navigate(this.currentIndex);

                if (this.media.images.length) {
                    this.activeIndex = 'image_0';

                    this.baseFile.type = 'image';

                    this.baseFile.path = this.media.images[0].large_image_url;
                } else if (this.media.videos.length) {
                    this.activeIndex = 'video_0';

                    this.baseFile.type = 'video';

                    this.baseFile.path = this.media.videos[0].video_url;
                }
            },

            computed: {
                lengthOfMedia() {
                    if (this.media.images.length) {
                        return [...this.media.images, ...this.media.videos].length > 5;
                    }
                }
            },

            methods: {
                productSliderModel() {
                    this.$refs.imageSliderModel.toggle();
                },

                onMediaLoad() {
                    this.isMediaLoading = false;
                },

                change(file, index) {
                    this.isMediaLoading = true;

                    if (file.type == 'videos') {
                        this.baseFile.type = 'video';

                        this.baseFile.path = file.video_url;

                        this.onMediaLoad();
                    } else {
                        this.baseFile.type = 'image';

                        this.baseFile.path = file.large_image_url;
                    }

                    this.activeIndex = index;
                },

                navigate(index) {
                    if (index > this.media.images.length) {
                        this.currentIndex = 1;
                    }

                    if (index < 1) {
                        this.currentIndex = this.media.images.length;
                    }

                    let slides = this.$refs.slides;

                    for (let i = 0; i < slides.length; i++) {
                        if (i == this.currentIndex - 1) {
                            continue;
                        }
                        
                        slides[i].style.display = 'none';
                    }

                    slides[this.currentIndex - 1].style.display = 'block';
                },
            },
        })
    </script>
@endpushOnce