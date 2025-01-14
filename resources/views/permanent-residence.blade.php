<x-front-layout :page-title="TITLE_PERM_RESIDENCE" :current-route-name="Route::currentRouteName()" bg-url="2020/08/page-title.jpg">
    <section
        class="elementor-section elementor-top-section elementor-element elementor-element-61750cc elementor-section-stretched elementor-section-full_width elementor-section-height-default elementor-section-height-default"
        data-id="61750cc" data-element_type="section" data-settings='{"stretch_section":"section-stretched"}'
        style="width: 634px; left: 0px">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-row">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-531ee38"
                    data-id="531ee38" data-element_type="column">
                    <div class="elementor-column-wrap elementor-element-populated">
                        <div class="elementor-widget-wrap">
                            <div class="elementor-element elementor-element-9c14a15 elementor-widget elementor-widget-visarzo_about"
                                data-id="9c14a15" data-element_type="widget" data-widget_type="visarzo_about.default">
                                <div class="elementor-widget-container">
                                    <section class="about-section">
                                        <div class="pattern-layer"
                                            style="
                          background-image: url(uploads/2020/07/pattern-1.png);
                        ">
                                        </div>
                                        <div class="auto-container">
                                            <!-- Note: remove 'align-items-center'-->
                                            <!-- BEFORE: <div class="row align-items-center clearfix">-->
                                            <div class="row clearfix">
                                                <!-- Note: remove the global 'auto' margin around this flex child -->
                                                <!--BEFORE: <div class="col-lg-6 col-md-12 col-sm-12 image-column">-->
                                                <div class="col-lg-6 col-md-12 col-sm-12 image-column"
                                                    style="margin:12px 0">
                                                    <div id="image_block_1">
                                                        <div class="image-box">
                                                            <figure class="image">
                                                                <img src="{{ asset('uploads/2020/07/federal-skilled-workers-1.jpg') }}"
                                                                    title="about-1" alt="about-1" />
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                                                    <div id="content_block_1">
                                                        <div class="content-box">
                                                            <div class="sec-title">
                                                                <!-- <p>who we are</p> -->
                                                                <h2 class="about_section">
                                                                    {{ TITLE_PERM_RESIDENCE }}
                                                                </h2>
                                                                <div class="dotted-box">
                                                                    <span class="dotted"></span>
                                                                    <span class="dotted"></span>
                                                                    <span class="dotted"></span>
                                                                </div>
                                                            </div>
                                                            <div class="bold-text">
                                                                <p>
                                                                    Canada’s population thrives through immigration. The
                                                                    government of Canada through the Immigration,
                                                                    Refugees
                                                                    and Citizenship Canada assists the entry of
                                                                    permanent
                                                                    residents in a way that maximizes their economic,
                                                                    social
                                                                    and cultural contribution to Canada, while at the
                                                                    same
                                                                    time protecting the health, safety and security of
                                                                    Canadians.
                                                                </p>
                                                                <p>
                                                                    There are different pathways that have been
                                                                    designed through which citizens of other countries
                                                                    can
                                                                    become Canadian Permanent Residents.
                                                                </p>
                                                                <p>
                                                                    If you are looking to make Canada your home, you
                                                                    might
                                                                    be eligible for one or some of the available
                                                                    pathways.
                                                                </p>

                                                                <span>
                                                                    <p>Canada's Express Entry system allows eligible
                                                                        Candidates apply for permanent residence under
                                                                        one of the following programs:</p>
                                                                </span>
                                                            </div>
                                                            <div class="btn-box">
                                                                <x-book-button>{{ TITLE_BOOK_BUTTON }}</x-book-button>
                                                            </div>
                                                            <div class="text">
                                                                <p>
                                                                    Today to speak with an Immigration expert and we
                                                                    would
                                                                    be able to guide you to achieve your Canadian
                                                                    immigration dreams.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-front-layout>
