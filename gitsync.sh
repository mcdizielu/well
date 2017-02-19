#!/usr/bin/env bash

action=$1

if [ "$action" == 'init' ]
    then
        git commit -a -m "Commiting changes before initializing subtrees"

        git subtree add --prefix=src/WellCommerce/Component/Breadcrumb git@github.com:WellCommerce/Breadcrumb.git master
        git subtree add --prefix=src/WellCommerce/Component/Collections git@github.com:WellCommerce/Collections.git master
        git subtree add --prefix=src/WellCommerce/Component/DataGrid git@github.com:WellCommerce/DataGrid.git master
        git subtree add --prefix=src/WellCommerce/Component/DataSet git@github.com:WellCommerce/DataSet.git master
        git subtree add --prefix=src/WellCommerce/Component/DoctrineEnhancer git@github.com:WellCommerce/DoctrineEnhancer.git master
        git subtree add --prefix=src/WellCommerce/Component/Form git@github.com:WellCommerce/Form.git master
        git subtree add --prefix=src/WellCommerce/Component/Search git@github.com:WellCommerce/Search.git master
        git subtree add --prefix=src/WellCommerce/Component/Serializer git@github.com:WellCommerce/Serializer.git master

        git subtree add --prefix=src/WellCommerce/Bundle/ApiBundle git@github.com:WellCommerce/ApiBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/AppBundle git@github.com:WellCommerce/AppBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/CatalogBundle git@github.com:WellCommerce/CatalogBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/CmsBundle git@github.com:WellCommerce/CmsBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/CoreBundle git@github.com:WellCommerce/CoreBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/CouponBundle git@github.com:WellCommerce/CouponBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/GeneratorBundle git@github.com:WellCommerce/GeneratorBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/OAuthBundle git@github.com:WellCommerce/OAuthBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/OrderBundle git@github.com:WellCommerce/OrderBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/ReviewBundle git@github.com:WellCommerce/ReviewBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/RoutingBundle git@github.com:WellCommerce/RoutingBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/SearchBundle git@github.com:WellCommerce/SearchBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/ShowcaseBundle git@github.com:WellCommerce/ShowcaseBundle.git master
        git subtree add --prefix=src/WellCommerce/Bundle/WishlistBundle git@github.com:WellCommerce/WishlistBundle.git master
fi

if [ "$action" == 'push' ]
    then
        git commit -a -m "Pushing changes before synchronizing subtrees"

        git subtree push --prefix=src/WellCommerce/Component/Breadcrumb git@github.com:WellCommerce/Breadcrumb.git master
        git subtree push --prefix=src/WellCommerce/Component/Collections git@github.com:WellCommerce/Collections.git master
        git subtree push --prefix=src/WellCommerce/Component/DataGrid git@github.com:WellCommerce/DataGrid.git master
        git subtree push --prefix=src/WellCommerce/Component/DataSet git@github.com:WellCommerce/DataSet.git master
        git subtree push --prefix=src/WellCommerce/Component/DoctrineEnhancer git@github.com:WellCommerce/DoctrineEnhancer.git master
        git subtree push --prefix=src/WellCommerce/Component/Form git@github.com:WellCommerce/Form.git master
        git subtree push --prefix=src/WellCommerce/Component/Search git@github.com:WellCommerce/Search.git master
        git subtree push --prefix=src/WellCommerce/Component/Serializer git@github.com:WellCommerce/Serializer.git master

        git subtree push --prefix=src/WellCommerce/Bundle/ApiBundle git@github.com:WellCommerce/ApiBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/AppBundle git@github.com:WellCommerce/AppBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/CatalogBundle git@github.com:WellCommerce/CatalogBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/CmsBundle git@github.com:WellCommerce/CmsBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/CoreBundle git@github.com:WellCommerce/CoreBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/CouponBundle git@github.com:WellCommerce/CouponBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/GeneratorBundle git@github.com:WellCommerce/GeneratorBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/OAuthBundle git@github.com:WellCommerce/OAuthBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/OrderBundle git@github.com:WellCommerce/OrderBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/ReviewBundle git@github.com:WellCommerce/ReviewBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/RoutingBundle git@github.com:WellCommerce/RoutingBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/SearchBundle git@github.com:WellCommerce/SearchBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/ShowcaseBundle git@github.com:WellCommerce/ShowcaseBundle.git master
        git subtree push --prefix=src/WellCommerce/Bundle/WishlistBundle git@github.com:WellCommerce/WishlistBundle.git master
fi

if [ "$action" == 'pull' ]
    then
        git commit -a -m "Pushing changes before pulling subtrees"

        git subtree pull --prefix=src/WellCommerce/Component/Breadcrumb git@github.com:WellCommerce/Breadcrumb.git master
        git subtree pull --prefix=src/WellCommerce/Component/Collections git@github.com:WellCommerce/Collections.git master
        git subtree pull --prefix=src/WellCommerce/Component/DataGrid git@github.com:WellCommerce/DataGrid.git master
        git subtree pull --prefix=src/WellCommerce/Component/DataSet git@github.com:WellCommerce/DataSet.git master
        git subtree pull --prefix=src/WellCommerce/Component/DoctrineEnhancer git@github.com:WellCommerce/DoctrineEnhancer.git master
        git subtree pull --prefix=src/WellCommerce/Component/Form git@github.com:WellCommerce/Form.git master
        git subtree pull --prefix=src/WellCommerce/Component/Search git@github.com:WellCommerce/Search.git master
        git subtree pull --prefix=src/WellCommerce/Component/Serializer git@github.com:WellCommerce/Serializer.git master

        git subtree pull --prefix=src/WellCommerce/Bundle/ApiBundle git@github.com:WellCommerce/ApiBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/AppBundle git@github.com:WellCommerce/AppBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/CatalogBundle git@github.com:WellCommerce/CatalogBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/CmsBundle git@github.com:WellCommerce/CmsBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/CoreBundle git@github.com:WellCommerce/CoreBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/CouponBundle git@github.com:WellCommerce/CouponBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/GeneratorBundle git@github.com:WellCommerce/GeneratorBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/OAuthBundle git@github.com:WellCommerce/OAuthBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/OrderBundle git@github.com:WellCommerce/OrderBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/ReviewBundle git@github.com:WellCommerce/ReviewBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/RoutingBundle git@github.com:WellCommerce/RoutingBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/SearchBundle git@github.com:WellCommerce/SearchBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/ShowcaseBundle git@github.com:WellCommerce/ShowcaseBundle.git master
        git subtree pull --prefix=src/WellCommerce/Bundle/WishlistBundle git@github.com:WellCommerce/WishlistBundle.git master
fi
