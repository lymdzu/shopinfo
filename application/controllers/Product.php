<?php

/**
 * 文件名称:Product.php
 * 摘    要:
 * 修改日期: 10/1/18
 */
class Product extends AdController
{
    public function __construct()
    {
        $this->vars['row'] = "goods";
        parent::__construct();
    }

    /**
     * 商品属性类目
     */
    public function category_list()
    {
        $this->load->model("ProductModel", "product", true);
        $cate_list = $this->product->get_product_category();
        $this->vars['category_list'] = $cate_list;
        $this->page("product/category_list.html");
    }

    public function list_cate()
    {
        $cate_id = $this->input->get("cate_id");
        $cate_type = $this->input->get("cate_type");
        $this->load->model("ProductModel", "product", true);
        $cate_info = $this->product->get_cate_info($cate_id);
        $pro_attr = $this->product->get_pro_cate_by_cateid($cate_id);
        $cate_list = $this->product->get_cate_list($cate_id, $cate_type);
        $this->vars['cate_list'] = $cate_list;
        $this->vars['cate_info'] = $cate_info;
        $this->vars['pro_attr'] = $pro_attr;
        $this->vars['cate_id'] = $cate_id;
        $this->vars['cate_type'] = $cate_type;
        $this->vars['cate'] = $pro_attr['product_cate' . $cate_type];
        $this->page("product/list_cate.html");
    }

    public function add_cate()
    {
        $cate_id = $this->input->post("cate_id");
        $cate_type = $this->input->post("cate_type");
        $cate_name = $this->input->post("cate_name");
        $this->load->model("ProductModel", "product", true);
        $insert_status = $this->product->add_cate($cate_id, $cate_type, $cate_name);
        if ($insert_status) {
            $this->json_result(REQUEST_SUCCESS, "类目添加成功");
        } else {
            $this->json_result(API_ERROR, "", "类目添加失败");
        }

    }

    public function edit_cate()
    {
        $id = $this->input->post("id");
        $cate_name = $this->input->post("name");
        $this->load->model("ProductModel", "product", true);
        $update_status = $this->product->edit_cate($id, $cate_name);
        if ($update_status) {
            $this->json_result(REQUEST_SUCCESS, "类目修改成功");
        } else {
            $this->json_result(API_ERROR, "", "类目修改失败");
        }
    }

    public function delete_cate()
    {
        $id = $this->input->post("id");
        $this->load->model("ProductModel", "product", true);
        $update_status = $this->product->delete_cate($id);
        if ($update_status) {
            $this->json_result(REQUEST_SUCCESS, "类目删除成功");
        } else {
            $this->json_result(API_ERROR, "", "类目删除失败");
        }
    }


    /**
     * 保存产品属性
     * @auther lymdzu@hotmail.com
     */
    public function save_product_cate()
    {
        $cate_id = $this->input->post("cate_id");
        $id = $this->input->post("id");
        $cate_name = $this->input->post("name");
        $product_cate1 = $this->input->post("product_cate1");
        $product_cate2 = $this->input->post("product_cate2");
        $product_cate3 = $this->input->post("product_cate3");
        $product_cate4 = $this->input->post("product_cate4");
        if (empty($cate_id)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少类目id");
        }
        if (empty($product_cate1)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少商品属性一");
        }
        if (empty($product_cate2)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少商品属性二");
        }
        if (empty($product_cate3)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少商品属性三");
        }
        if (empty($product_cate4)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少商品属性四");
        }
        $this->load->model('ProductModel', "product", true);
        if (empty($id)) {
            $save_status = $this->product->save_prodcut_cate($cate_id, $cate_name, $product_cate1, $product_cate2, $product_cate3, $product_cate4);
        } else {
            $save_status = $this->product->update_prodcut_cate($id, $cate_id, $cate_name, $product_cate1, $product_cate2, $product_cate3, $product_cate4);
        }

        if ($save_status) {
            $this->json_result(REQUEST_SUCCESS, "商品属性添加成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器出错");
        }
    }

    public function add_product()
    {
        $this->vars['page'] = "add_product";
        $this->load->model('GoodsModel', "goods", true);
        $admin = $this->admin_company();
        $brand_list = $this->goods->get_brand_list(0, 1000000, $admin['company']);
        $first_level = $this->goods->get_goods_type_list(1);
        $this->vars['first_level'] = $first_level;
        $this->vars['brandlist'] = $brand_list;
        $this->page("product/add_product.html");
    }

    /**
     * 添加商品
     * @auther lymdzu@hotmail.com
     */
    public function save_product()
    {
        $product_name = $this->input->post("product_name");
        $brand = $this->input->post("brand");
        $category = $this->input->post("category");
        $product_cate1 = $this->input->post("product_cate1");
        $product_cate2 = $this->input->post("product_cate2");
        $product_cate3 = $this->input->post("product_cate3");
        $product_cate4 = $this->input->post("product_cate4");
        $product_pic = $this->input->post("product_pic");
        if (empty($product_name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写商品名称");
        }
        if (empty($brand)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请选择商品品牌");
        }
        if (empty($category)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请选择商品分类类目");
        }
        if (empty($product_cate1)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写商品属性一");
        }
        if (empty($product_cate2)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写商品属性二");
        }
        if (empty($product_cate3)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写商品属性三");
        }
        if (empty($product_cate4)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写商品属性四");
        }
        if (empty($product_pic)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请上传商品图片");
        }
        $this->load->model('ProductModel', "product", true);
        $this->product->save_product($product_name, $brand, $category, $product_cate1, $product_cate2, $product_cate3, $product_cate4, $product_pic);
    }

    public function get_pro_cate()
    {
        $cate_id = $this->input->post("cate_id");
        $this->load->model('ProductModel', "product", true);
        $cate_list = $this->product->get_pro_cate_by_cateid($cate_id);
        $pro_cate = array();
        if (!empty($cate_list)) {
            $pro_cate[$cate_list['product_cate1']] = $this->product->get_cate_by_type($cate_id, 1);
            $pro_cate[$cate_list['product_cate2']] = $this->product->get_cate_by_type($cate_id, 2);
            $pro_cate[$cate_list['product_cate3']] = $this->product->get_cate_by_type($cate_id, 3);
            $pro_cate[$cate_list['product_cate4']] = $this->product->get_cate_by_type($cate_id, 4);
        }
        if (!empty($cate_list)) {
            $this->json_result(REQUEST_SUCCESS, $pro_cate);
        } else {
            $this->json_result(API_ERROR, "", "尚未设置此类型商品属性");
        }
    }

    public function product_pic()
    {
        $targetDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file_material_tmp';
        $uploadDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file';
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }
        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $oldName = $fileName;
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        // Remove old temp files
        if ($cleanupTargetDir) {
            $dir = opendir($targetDir);
            if (!is_dir($targetDir) || !$dir) {
                $this->json_result(API_ERROR, "", "不能写入文件");
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $done = true;
        for ($index = 0; $index < $chunks; $index++) {
            if (!file_exists("{$filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }
        if ($done) {
            $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $oldName;

            if (!$out = @fopen($uploadPath, "wb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "图片存储失败");
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $chunks; $index++) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $pathInfo = pathinfo($fileName);
            $filetype = strtoupper($pathInfo['extension']);
            $size = sprintf("%.2f", $_REQUEST['size'] / 1024 / 1024);
            $this->json_result(REQUEST_SUCCESS, $fileName);
        }
    }
}