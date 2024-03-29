/**
 * 添加常用通用数据列
 *
 * TODO 需修改table_name及name_of_last_column为实际值
 *
 * 创建/删除/最后操作时间，及操作用户ID
 */
ALTER TABLE `table_name`
    ADD COLUMN `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间' AFTER `name_of_last_column`,
    ADD COLUMN `time_delete` datetime NULL COMMENT '删除时间' AFTER `time_create`,
    ADD COLUMN `time_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后操作时间' AFTER `time_delete`,
    ADD COLUMN `creator_id` int(11) UNSIGNED NOT NULL COMMENT '创建者用户ID' AFTER `time_edit`,
    ADD COLUMN `operator_id` int(11) UNSIGNED NULL COMMENT '最后操作者用户ID' AFTER `creator_id`;
