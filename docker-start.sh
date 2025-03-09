#!/bin/bash

# 确保脚本抛出遇到的错误
set -e

# 构建并启动容器
docker-compose up -d

echo "服务已启动完成！"
echo "访问 http://localhost 进行系统配置" 