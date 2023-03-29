<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);

        /* Start Pages */
        $this->call(HomePageSeeder::class);

        $this->call(AboutOurWashesPageSeeder::class);
        $this->call(HungryBearMarketPageSeeder::class);
        $this->call(SelfServeCarWashPageSeeder::class);
        $this->call(TouchlessCarWashPageSeeder::class);
        $this->call(TopTierGasPageSeeder::class);
        $this->call(TunnelCarWashPageSeeder::class);

        $this->call(WashClubsPageSeeder::class);
        $this->call(WashClubsUnlimitedWashClubPageSeeder::class);
        $this->call(WashClubsForHireUnlimitedWashClubPageSeeder::class);

        $this->call(CommercialProgramsPageSeeder::class);
        $this->call(CommercialProgramsCarDealershipProgramPageSeeder::class);
        $this->call(CommercialProgramsFleetWashProgramPageSeeder::class);

        $this->call(ShopPageSeeder::class);
        $this->call(ShopBrandedMerchandisePageSeeder::class);
        $this->call(ShopPawPacksTicketBooksPageSeeder::class);

        $this->call(CommunityCommitmentPageSeeder::class);
        $this->call(CommunityCommitmentGuardReservesPageSeeder::class);
        $this->call(CommunityCommitmentDiversityInclusionPageSeeder::class);
        $this->call(CommunityCommitmentWashGreenPageSeeder::class);
        $this->call(CommunityCommitmentCarWashFundraiserPageSeeder::class);
        $this->call(CommunityCommitmentCharitableDonationsPageSeeder::class);

        $this->call(OurCompanyPageSeeder::class);
        $this->call(OurCompanyCareersPageSeeder::class);
        $this->call(OurCompanyOurHistoryPageSeeder::class);
        $this->call(OurCompanyLeadershipPageSeeder::class);
        $this->call(OurCompanyPressCenterPageSeeder::class);
        $this->call(OurCompanyNewsPageSeeder::class);

        $this->call(SupportPageSeeder::class);
        $this->call(SupportContactUsPageSeeder::class);
        $this->call(SupportPoliciesPageSeeder::class);

        $this->call(LocationsPageSeeder::class);
        /* End Pages */

        /* Page Updates */
        $this->call(LocationsPageUpdateSeeder::class);
        /* End Page Updates */

        $this->call(ServicesTableSeeder::class);
        $this->call(LocationsTableSeeder::class);

        /**
         * Product Variants
         */
        $this->call(ProductVariantsSeeder::class);

        if (config('app.debug')) {
            $this->call(DummyOrdersTableSeeder::class);
        }

        $this->call(FileCategoriesTableSeeder::class);
        $this->call(FilesTableSeeder::class);

        $this->call(PublishPagesPermissionRoleTableUpdateSeeder::class);

        /*
         * Ticket 540 Promo Popovers
         */
        $this->call(SplashesDataTypesTableUpdateSeeder::class);
        $this->call(SplashesDataRowsTableUpdateSeeder::class);
        $this->call(SplashesMenuItemsTableUpdateSeeder::class);
        $this->call(SplashesPermissionsUpdateSeeder::class);

        /*
         * Ticket 571 Essential Page Category
         */
        $this->call(EssentialPageCategoryPermissionsTableUpdateSeeder::class);
        $this->call(EssentialPageCategoryPermissionRoleTableUpdateSeeder::class);
        $this->call(EssentialPagesCategoryUpdateSeeder::class);

        /*
         * Ticket 568 Splashes Valid URL field
         */
        $this->call(SplashesValidUrlDataRowsTableUpdateSeeder::class);

        /*
         * Ticket 554 Public Files
         */
        $this->call(PublicFilesDataTypesTableUpdateSeeder::class);
        $this->call(PublicFilesDataRowsTableUpdateSeeder::class);
        $this->call(PublicFileCategoriesTableUpdateSeeder::class);
        $this->call(PublicFilesMenuItemsTableUpdateSeeder::class);
        $this->call(PublicFilesPermissionsUpdateSeeder::class);

        /*
         * Ticket 584 Overviews
         */
        $this->call(OverviewsPagesUpdateSeeder::class);

        /*
         * Ticket 594 Delete File Throws An Exception
         */
        $this->call(DeleteFileDataRowsTableUpdateSeeder::class);

        /*
         * Ticket 802 Charitable Donations to Giving Back
         */
        $this->call(CharitableDonationsPageUpdateSeeder::class);

        /*
         * Ticket 842 Add Unlimited Wash Clubs Membership Page
         */
        $this->call(ShopWashClubMembershipsPageTicket842::class);

        /**
         * SSO Updates
         */
        $this->call(SSOUpdatesSeeder::class);

        $this->call(CustomerRoleSeeder::class);
        $this->call(WebsiteAdministratorRole::class);
        $this->call(ProductNamesSeeder::class);
        $this->call(RemoveLocation1014::class);
        $this->call(AdminOrderStatus::class);
        $this->call(ServiceSlugsUpdate::class);
        $this->call(AllowNonUniqueCouponCodes::class);
        $this->call(AddSiteManagersToLeadershipPage::class);
        $this->call(ShopAllProductsPageTicket808::class);
        $this->call(AddLocationsTemporarilyClosedDataRow::class);
        $this->call(AdminOrdersFilters::class);
        $this->call(StaffRole::class);
        $this->call(ProductVolumePricing::class);

        /*
         * Ticket 841 Update Shop Menu Overview Page
         */
        $this->call(ShopWashCardsAndTicketBooksPageTicket841::class);

        /*
        * Ticket 822 Club Status Helper Text
        */
        $this->call(AdminOrderClubStatus::class);
        $this->call(DisallowDeleteCoupon::class);

        $this->call(BipPromoAndLimitedProductVariants::class);
        $this->call(QaUser::class);
        $this->call(LocationsAdminUpdateSeeder::class);

        /* Start Ticket G198 - https://bitbucket.org/flowerpress/gateway.brownbear.com/issues/198/updates-to-user-role-permissions */
        $this->call(AdminBulkDeletePermission::class);
        $this->call(AdminEditSalesTaxPermission::class);
        $this->call(RolesITAndTraningManagerAndSiteManager::class);
        $this->call(RoleSuperAdminPermissions::class);
        $this->call(RoleAdminPermissions::class);
        $this->call(RoleITPermissions::class);
        $this->call(RoleStaffPermissions::class);
        $this->call(RoleSiteManagerPermissions::class);
        $this->call(RoleTrainingManagerPermissions::class);
        /* End Ticket G198 */

        $this->call(AddShippedAtColumnToOrdersAdmin::class);

        $this->call(AddLocationMetaSameAsDataRow::class);

        $this->call(AdminLogsMenuItem::class);

        if (env('APP_ENV') === 'dusk') {
            $this->call(DuskSeeder::class);
        }

        $this->call(WashCardPageSeeder::class);

        $this->call(OrderUpdateDontRequireShippingData::class);
    }
}
