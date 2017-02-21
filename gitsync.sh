#!/usr/bin/env bash

action=$1
components=("Breadcrumb" "Collections" "DataGrid" "DataSet" "DoctrineEnhancer" "Form" "Search" "Serializer")
bundles=("ApiBundle" "AppBundle" "CatalogBundle" "CmsBundle" "CoreBundle" "CouponBundle" "GeneratorBundle" "OAuthBundle" "OrderBundle" "ReviewBundle" "RoutingBundle" "SearchBundle" "ShowcaseBundle" "WishlistBundle")

if [ "$action" == 'test' ]
    then
        for i in "${components[@]}"
        do
            echo $i
        done
fi

if [ "$action" == 'init' ]
    then
        rm -rf src/WellCommerce/Bundle/*
        rm -rf src/WellCommerce/Component/*
        git commit -a -m "Commiting changes before initializing subtrees"

        for i in "${components[@]}"
        do
            git subtree add --prefix=src/WellCommerce/Component/$i git@github.com:WellCommerce/$i.git master
        done

        for i in "${bundles[@]}"
        do
            git subtree add --prefix=src/WellCommerce/Bundle/$i git@github.com:WellCommerce/$i.git master
        done
fi

if [ "$action" == 'push' ]
    then
        git commit -a -m "Pushing changes before synchronizing subtrees"

        for i in "${components[@]}"
        do
            git subtree push --prefix=src/WellCommerce/Component/$i git@github.com:WellCommerce/$i.git master
        done

        for i in "${bundles[@]}"
        do
            git subtree push --prefix=src/WellCommerce/Bundle/$i git@github.com:WellCommerce/$i.git master
        done
fi
