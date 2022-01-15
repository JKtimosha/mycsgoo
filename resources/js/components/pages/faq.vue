<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="content">
            <div class="main-width">
                <div class="page-title"><span>FAQ</span></div>
                <div>
                    <div rel="faq_1" class="faq-item" :class="[this.faq_1 ? 'active' : '']" v-on:click="open(1)">
                        <div class="faq-heading">{{ $t('faq.faq_1') }}
                        </div>
                        <div aria-hidden="true" class="faq-content rah-static--height-zero"
                             style="height: auto; overflow: hidden;">
                            <div :style="{display: this.faq_1 ? 'block' : 'none'}">
                                <div class="text" v-html="$t('faq.answer_1')"></div>
                            </div>
                        </div>
                    </div>
                    <div rel="faq_2"  class="faq-item" :class="[this.faq_2 ? 'active' : '']" v-on:click="open(2)">
                        <div class="faq-heading">{{ $t('faq.faq_2') }}</div>
                        <div aria-hidden="true" class="faq-content rah-static--height-zero"
                             style="height: auto; overflow: hidden;">
                            <div :style="{display: this.faq_2 ? 'block' : 'none'}">
                                <div class="text" v-html="$t('faq.answer_2')"></div>
                            </div>
                        </div>
                    </div>
                    <div rel="faq_3"  class="faq-item" :class="[this.faq_3 ? 'active' : '']" v-on:click="open(3)">
                        <div class="faq-heading">{{ $t('faq.faq_3') }}
                        </div>
                        <div aria-hidden="true" class="faq-content rah-static--height-zero"
                             style="height: auto; overflow: hidden;">
                            <div :style="{display: this.faq_3 ? 'block' : 'none'}">
                                <div class="text" v-html="$t('faq.answer_3')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div rel="faq_4"  class="faq-item" :class="[this.faq_4 ? 'active' : '']" v-on:click="open(4)">
                        <div class="faq-heading">{{ $t('faq.faq_4') }}</div>
                        <div aria-hidden="true" class="faq-content rah-static--height-zero"
                             style="height: auto; overflow: hidden;">
                            <div :style="{display: this.faq_4 ? 'block' : 'none'}">
                                <div class="text" v-html="$t('faq.answer_4')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div rel="faq_5"  class="faq-item" :class="[this.faq_5 ? 'active' : '']" v-on:click="open(5)">
                        <div class="faq-heading">{{ $t('faq.faq_5') }}</div>
                        <div aria-hidden="true" class="faq-content rah-static--height-zero"
                             style="height: auto; overflow: hidden;">
                            <div :style="{display: this.faq_5 ? 'block' : 'none'}">
                                <div class="text" v-html="$t('faq.answer_5')">
                                </div>
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
        data() {
            return {
                faq_1: false,
                faq_2: false,
                faq_3: false,
                faq_4: false,
                faq_5: false
            }
        },
        methods: {
            open(id) {
                const vm = this;
                switch (id) {
                    case 1:
                        if (vm.faq_1) {
                            vm.faq_1 = false;
                        } else {
                            vm.faq_1 = true;
                        }
                        break;
                    case 2:
                        if (vm.faq_2) {
                            vm.faq_2 = false;
                        } else {
                            vm.faq_2 = true;
                        }
                        break;
                    case 3:
                        if (vm.faq_3) {
                            vm.faq_3 = false;
                        } else {
                            vm.faq_3 = true;
                        }
                        break;
                    case 4:
                        if (vm.faq_4) {
                            vm.faq_4 = false;
                        } else {
                            vm.faq_4 = true;
                        }
                        break;
                    case 5:
                        if (vm.faq_5) {
                            vm.faq_5 = false;
                        } else {
                            vm.faq_5 = true;
                        }
                        break;
                }
            }
        }
    }
</script>