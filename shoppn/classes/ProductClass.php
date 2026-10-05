<?php

require_once __DIR__ . "/../core/db_class.php";

class ProductClass extends Database
{
    public function addBrand($name)
    {
        return $this->execute("INSERT INTO brands (brand_name) VALUES (?)", [$name]);
    }

    public function getAllBrands()
    {
        return $this->fetchAll("SELECT * FROM brands ORDER BY brand_name ASC");
    }

    public function updateBrand($id, $name)
    {
        return $this->execute("UPDATE brands SET brand_name = ? WHERE brand_id = ?", [$name, $id]);
    }

    public function deleteBrand($id)
    {
        return $this->execute("DELETE FROM brands WHERE brand_id = ?", [$id]);
    }


        public function addCategory($name)
    {
        return $this->execute("INSERT INTO categories (cat_name) VALUES (?)", [$name]);
    }

    public function getAllCategories()
    {
        return $this->fetchAll("SELECT * FROM categories ORDER BY cat_name ASC");
    }

    public function getCategoryById($id)
    {
        return $this->fetchOne("SELECT * FROM categories WHERE cat_id = ?", [$id]);
    }

    public function updateCategory($id, $name)
    {
        return $this->execute("UPDATE categories SET cat_name = ? WHERE cat_id = ?", [$name, $id]);
    }

        public function getLatestBrandIdByName($name)
    {
        $row = $this->fetchOne("SELECT brand_id FROM brands WHERE brand_name = ? ORDER BY brand_id DESC", [$name]);
        return $row ? (int)$row['brand_id'] : 0;
    }

    public function setBrandCategories($brandId, $catIds)
    {
        $this->execute("DELETE FROM brand_categories WHERE brand_id = ?", [$brandId]);
        foreach ($catIds as $catId) {
            $this->execute("INSERT INTO brand_categories (brand_id, cat_id) VALUES (?, ?)", [$brandId, (int)$catId]);
        }
        return true;
    }

        public function deleteCategory($id)
    {
        return $this->execute("DELETE FROM categories WHERE cat_id = ?", [$id]);
    }

    public function getBrandsWithCategories()
    {
        return $this->fetchAll("
            SELECT b.brand_id, b.brand_name,
                   GROUP_CONCAT(c.cat_name ORDER BY c.cat_name SEPARATOR ', ') AS categories,
                   GROUP_CONCAT(c.cat_id) AS cat_ids
            FROM brands b
            LEFT JOIN brand_categories bc ON bc.brand_id = b.brand_id
            LEFT JOIN categories c ON c.cat_id = bc.cat_id
            GROUP BY b.brand_id, b.brand_name
            ORDER BY b.brand_name ASC
        ");
    }
}