export function getTagIdsFromArray(tags) {
    let tagIds = [];

    tags.forEach((tag) => {
        tagIds = [...tagIds, tag.id];
    });

    return tagIds;
}
