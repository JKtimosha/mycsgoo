<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="content">
            <div class="main-width">
                <div class="page-title">{{ $t('agreement.head') }}</div>
                <div class="rules">
                    <span v-html="$t('agreement.desc_1')"></span>
                    {{ $root.config.domain }}
                    <span v-html="$t('agreement.desc_2')"></span>
                    {{ $root.config.domain }}
                    <span v-html="$t('agreement.desc_3')"></span>
                    {{ $root.config.site_name_protocol }}<span v-html="$t('agreement.desc_4')"></span>
                    {{ $root.config.domain }}
                    <span v-html="$t('agreement.desc_5')"></span>
                    {{ $root.config.site_name_protocol }}<span v-html="$t('agreement.desc_6')"></span>
                    {{ $root.config.support_email }}
                    <span v-html="$t('agreement.desc_7')"></span>
                    {{ $root.config.site_name_protocol }}<span v-html="$t('agreement.desc_8')"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {}
</script>