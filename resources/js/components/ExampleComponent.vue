<template>
    <div class="row my-3 mx-0">
        <div class="col-9">
            <!-- @if($show_subCatrgories !== 1 && $show_Catrgories == 1 && $show_products !== 1 && $step == 1) -->
            <div class="w3l-gallery2">
                <div class="burger galleryks py-3">
                    <div class="container py-lg-3 py-md-3">
                        <div class="row no-gutters masonry swiper swiper_m">
                            <h3>القسم الرئيسي</h3>
                                <div class="swiper-wrapper" aria-live="polite">

                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            categories: Array,
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
