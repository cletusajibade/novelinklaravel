<x-front-layout :page-title="TITLE_FED_SKILLED_WORKER" :current-route-name="Route::currentRouteName()" bg-url="2020/08/page-title.jpg">
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
                                            <div class="row align-items-center clearfix">
                                                <div class="col-lg-6 col-md-12 col-sm-12 image-column">
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
                                                                    {{ TITLE_FED_SKILLED_WORKER }}
                                                                </h2>
                                                                <div class="dotted-box">
                                                                    <span class="dotted"></span>
                                                                    <span class="dotted"></span>
                                                                    <span class="dotted"></span>
                                                                </div>
                                                            </div>
                                                            <div class="bold-text">
                                                                <p>
                                                                    Canada selects applicants based on their ability to
                                                                    become economically established in Canada.
                                                                    Are you a professional? Do you have some years of
                                                                    work
                                                                    experience? Then you might be eligible to come to
                                                                    Canada
                                                                    as a Federal Skilled Worker.

                                                                </p>
                                                            </div>
                                                            <div class="btn-box">
                                                                <x-book-button
                                                                    url="{{ route('consultation.create') }}">{{ TITLE_BOOK_BUTTON }}</x-book-button>
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
