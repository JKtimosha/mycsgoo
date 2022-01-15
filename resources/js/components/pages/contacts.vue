<template>
    <div>
        <div class="branding profile-brand">
            <div class="main-width clear"></div>
        </div>
        <div class="content">
            <div class="main-width">
                <div class="page-title">{{ $t('contacts.head') }}</div>
                <div class="contacts">
                    <div class="grid second">
                        <div class="item clear">
                            <div class="col">Beneficiary:</div>
                            <div class="col">INTERNATIONAL BUSINESS SYSTEMS S.R.L.</div>
                        </div>
                        <div class="item clear">
                            <div class="col">Reg. number:</div>
                            <div class="col">3-102-693823</div>
                        </div>
                        <div class="item clear">
                            <div class="col">Beneficiary’s address:</div>
                            <div class="col">SAN JOSE-SANTA ANA,THREE HUNDRED AND<br>FIFTY METERS OF THE RESTAURANT
                                CEVICHE
                                DEL
                                REY, COSTA-RICA
                            </div>
                        </div>
                        <br>
                        <div class="item clear">
                            <div class="col">{{ $t('contacts.domain') }}:</div>
                            <div class="col">{{ $root.config.domain }}</div>
                        </div>
                        <div class="item clear">
                            <div class="col">{{ $t('contacts.support') }}:</div>
                            <div class="col">{{ $root.config.support_email }}</div>
                        </div>
                        <div class="item clear">
                            <div class="col">{{ $t('contacts.group') }}:</div>
                            <div class="col">{{ $root.config.vk_group }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {}
</script>