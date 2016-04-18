SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
PROJECT_DIR=$( cd "$(dirname "$SCRIPT_DIR")" && pwd)
BOWER_DIR=$( cd "$PROJECT_DIR/bower_components" && pwd)
NODE_MODULES_DIR=$( cd "$PROJECT_DIR/node_modules" && pwd)

php "$SCRIPT_DIR/transform-webfont.php" "$BOWER_DIR/fontawesome/fonts/fontawesome-webfont.svg" "$BOWER_DIR/fontawesome/less/variables.less" "$SCRIPT_DIR/webfont.paths.svg"
node "$NODE_MODULES_DIR/svgo/bin/svgo" --pretty --disable=mergePaths --disable=cleanupIDs  "$SCRIPT_DIR/webfont.paths.svg" "$SCRIPT_DIR/webfont.paths.min.svg"
php "$SCRIPT_DIR/extract-paths.php" "$SCRIPT_DIR/webfont.paths.min.svg" "$PROJECT_DIR/fontawesome-markers.min.js" "$PROJECT_DIR/fontawesome-markers.json"
