# Changelog

## [1.0.0](https://github.com/dynamic/silverstripe-foxy-orders/tree/1.0.0) (2019-10-30)

[Full Changelog](https://github.com/dynamic/silverstripe-foxy-orders/compare/0ac7b6271ffc1e5301b32bcef796ebaff81bdc06...1.0.0)

**Implemented enhancements:**

- FEATURE allow viewing of ReadOnly order data [\#41](https://github.com/dynamic/silverstripe-foxy-orders/issues/41)
- ENHANCEMENT Product relation linking [\#34](https://github.com/dynamic/silverstripe-foxy-orders/issues/34)
- FEATURE Order - parse customer data [\#29](https://github.com/dynamic/silverstripe-foxy-orders/issues/29)
- REFACTOR - Order::parseOrderDetails\(\) - clean up [\#22](https://github.com/dynamic/silverstripe-foxy-orders/issues/22)
- REFACTOR - ProductOption - belongs\_many\_many Product [\#21](https://github.com/dynamic/silverstripe-foxy-orders/issues/21)
- REFACTOR - Namespace update to `Dynamic\Foxy\Orders` [\#20](https://github.com/dynamic/silverstripe-foxy-orders/issues/20)
- Order - parse order info into OrderItem to track products sold [\#17](https://github.com/dynamic/silverstripe-foxy-orders/issues/17)
- Order - PermissionProvider [\#15](https://github.com/dynamic/silverstripe-foxy-orders/issues/15)
- NEW OrderAdmin [\#13](https://github.com/dynamic/silverstripe-foxy-orders/issues/13)
- NEW Updated docs and policies [\#11](https://github.com/dynamic/silverstripe-foxy-orders/issues/11)
- CI - setup Jenkins testing [\#6](https://github.com/dynamic/silverstripe-foxy-orders/issues/6)
- DataTestController - replace curl with guzzle [\#5](https://github.com/dynamic/silverstripe-foxy-orders/issues/5)
- NEW CustomerExtension [\#3](https://github.com/dynamic/silverstripe-foxy-orders/issues/3)
- Remove legacy sso\(\) function from FoxyController [\#2](https://github.com/dynamic/silverstripe-foxy-orders/issues/2)
- NEW FoxyController [\#1](https://github.com/dynamic/silverstripe-foxy-orders/issues/1)
- REFACTOR provide flexible transction decryption [\#31](https://github.com/dynamic/silverstripe-foxy-orders/pull/31) ([muskie9](https://github.com/muskie9))

**Fixed bugs:**

- BUG ProductName needs to be HTMLText [\#48](https://github.com/dynamic/silverstripe-foxy-orders/issues/48)
- BUG OrderHistoryController - setOrderPaginatedList\(\) throws an error if a user accesses the page when not logged in [\#46](https://github.com/dynamic/silverstripe-foxy-orders/issues/46)
- BUG OrderDetailFactory - Price not being set [\#40](https://github.com/dynamic/silverstripe-foxy-orders/issues/40)
- BUG OrderFactory - Response is not being set [\#38](https://github.com/dynamic/silverstripe-foxy-orders/issues/38)
- BUG OrderDetail - ProductID is not being set during order parsing [\#36](https://github.com/dynamic/silverstripe-foxy-orders/issues/36)

**Closed issues:**

- BUG OrderDetailFactory - Update product query to use FoxyHelper::getProducts\(\) [\#37](https://github.com/dynamic/silverstripe-foxy-orders/issues/37)

**Merged pull requests:**

- BUGFIX update ProductName to HTMLVarchar [\#50](https://github.com/dynamic/silverstripe-foxy-orders/pull/50) ([muskie9](https://github.com/muskie9))
- BUGFIX OrderHIstory - setOrderPaginatedList\(\) no longer errors if log… [\#47](https://github.com/dynamic/silverstripe-foxy-orders/pull/47) ([jsirish](https://github.com/jsirish))
- BUGFIX Member has\_many Orders [\#45](https://github.com/dynamic/silverstripe-foxy-orders/pull/45) ([jsirish](https://github.com/jsirish))
- FEATURE README badges [\#44](https://github.com/dynamic/silverstripe-foxy-orders/pull/44) ([jsirish](https://github.com/jsirish))
- BUGFIX response not recording [\#43](https://github.com/dynamic/silverstripe-foxy-orders/pull/43) ([muskie9](https://github.com/muskie9))
- ENHANCEMENT viewable orders in order history admin [\#42](https://github.com/dynamic/silverstripe-foxy-orders/pull/42) ([muskie9](https://github.com/muskie9))
- BUGFIX relations not being set during order parsing [\#35](https://github.com/dynamic/silverstripe-foxy-orders/pull/35) ([muskie9](https://github.com/muskie9))
- BUGFIX remove rc4crypt as it’s in the feed parser package [\#33](https://github.com/dynamic/silverstripe-foxy-orders/pull/33) ([muskie9](https://github.com/muskie9))
- FEATURE Order - parse customer data [\#30](https://github.com/dynamic/silverstripe-foxy-orders/pull/30) ([jsirish](https://github.com/jsirish))
- FEATURE OrderHistory - page type to display user’s order history [\#28](https://github.com/dynamic/silverstripe-foxy-orders/pull/28) ([jsirish](https://github.com/jsirish))
- BUGFIX Additional namespace updates, routes [\#27](https://github.com/dynamic/silverstripe-foxy-orders/pull/27) ([jsirish](https://github.com/jsirish))
- FoxyController - corrected namespace in routes.yml [\#26](https://github.com/dynamic/silverstripe-foxy-orders/pull/26) ([jsirish](https://github.com/jsirish))
- refactor - update namespaces [\#25](https://github.com/dynamic/silverstripe-foxy-orders/pull/25) ([jsirish](https://github.com/jsirish))
- new GitHub issue templates [\#24](https://github.com/dynamic/silverstripe-foxy-orders/pull/24) ([jsirish](https://github.com/jsirish))
- composer - add authors [\#23](https://github.com/dynamic/silverstripe-foxy-orders/pull/23) ([jsirish](https://github.com/jsirish))
- NEW implement OrderDetail, OrderOption [\#19](https://github.com/dynamic/silverstripe-foxy-orders/pull/19) ([jsirish](https://github.com/jsirish))
- Order - implement PermissionProvider [\#16](https://github.com/dynamic/silverstripe-foxy-orders/pull/16) ([jsirish](https://github.com/jsirish))
- OrderAdmin - initial build [\#14](https://github.com/dynamic/silverstripe-foxy-orders/pull/14) ([jsirish](https://github.com/jsirish))
- update documents and policies [\#12](https://github.com/dynamic/silverstripe-foxy-orders/pull/12) ([jsirish](https://github.com/jsirish))
- refactor - order processing [\#10](https://github.com/dynamic/silverstripe-foxy-orders/pull/10) ([jsirish](https://github.com/jsirish))
- CustomerExtension - record orders to member records [\#9](https://github.com/dynamic/silverstripe-foxy-orders/pull/9) ([jsirish](https://github.com/jsirish))
- DataTestController - use guzzle in favor of curl [\#8](https://github.com/dynamic/silverstripe-foxy-orders/pull/8) ([jsirish](https://github.com/jsirish))
- jenkins CI setup [\#7](https://github.com/dynamic/silverstripe-foxy-orders/pull/7) ([jsirish](https://github.com/jsirish))
- remove legacy SSO functions [\#4](https://github.com/dynamic/silverstripe-foxy-orders/pull/4) ([jsirish](https://github.com/jsirish))



\* *This Changelog was automatically generated by [github_changelog_generator](https://github.com/github-changelog-generator/github-changelog-generator)*
