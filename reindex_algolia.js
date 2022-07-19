/* eslint-disable import/no-extraneous-dependencies */
/* eslint-disable no-console */
const fs = require('fs');
const { JSDOM } = require('jsdom');
const glob = require('glob');
const algoliasearch = require('algoliasearch');

const { readDOMDepth } = require('./source/__source/assets/js/search/utils');

const algoliaClient = algoliasearch('C8U4P0CC81', process.env.ALGOLIA_SEARCH_ADMIN_KEY);
const articlesIndex = algoliaClient.initIndex('articles');

const getCommentsFromDocument = (document) => (
    Array.from(document.querySelector('body').childNodes)
).slice().reverse().find((t) => t.nodeType === 8).textContent;

const filePathToUrl = (filePath) => filePath && filePath.split('/').slice(0, -1).slice(1).join('/');

articlesIndex.clearObjects().then(() => {
    const blackListIndexPath = [
        'strony', 'aktualnosci',
    ];
    glob('build_local/*/**/index.html', (errGlob, matches) => {
        if (errGlob) {
            console.error(errGlob);
            return;
        }

        matches.forEach((filePath) => {
            const splittedPath = filePath.split('/');
            if (!splittedPath.some((a) => (
                // eslint-disable-next-line no-restricted-globals
                !isNaN(a) || blackListIndexPath.includes(a)
            ) && splittedPath.length > 3)) {
                console.log(filePath);

                fs.readFile(filePath, (errFile, data) => {
                    if (errFile) {
                        console.error(errFile);
                        return;
                    }

                    const dom = new JSDOM(data);
                    const { document } = dom.window;

                    const tagsStr = getCommentsFromDocument(document);
                    const tags = tagsStr.replace(/, /g, ',').replace('TAGS: ', '').split(',').filter((l) => l.length);

                    const header = document.querySelector('h1');
                    let lastParentId = header?.getAttribute('id');
                    const collectedData = {};
                    collectedData[lastParentId] = {
                        path: header?.textContent || '',
                        content: '',
                        section: lastParentId,
                        redirect: filePathToUrl(filePath),
                        tags,
                        objectID: Buffer.from(filePathToUrl(filePath) + lastParentId).toString('base64'),
                    };

                    let pathToHeader = [];
                    let lastTagLevel = 1;
                    readDOMDepth(header, (el, isChild) => {
                        if (el.parsed || el?.tagName?.includes('SUP') || el?.className?.includes?.('toc') || el?.getAttribute?.('href')?.includes('fnref')) {
                            return true;
                        }

                        const id = el?.getAttribute?.('id');
                        if (id?.includes('fn')) {
                            return true;
                        }
                        if (/*  && */id) {
                            if (el?.tagName?.startsWith('H') && el.tagName.length === 2) {
                                const tagLevel = parseInt(el?.tagName[1], 10);
                                if (pathToHeader.length > 1) {
                                    if (lastTagLevel === tagLevel) {
                                        pathToHeader = pathToHeader.slice(0, -1);
                                    }
                                    if (lastTagLevel > tagLevel) {
                                        pathToHeader = pathToHeader.slice(0, -2);
                                    }
                                }

                                pathToHeader.push(
                                    el.textContent,
                                );
                                lastTagLevel = tagLevel;
                            }

                            const lastCollectedData = collectedData[lastParentId];
                            lastParentId = id;

                            if (lastCollectedData && lastCollectedData.content) {
                                lastCollectedData.content = lastCollectedData?.content.replace(/\s/g, ' ');
                            }

                            if (!Object.prototype.hasOwnProperty.call(collectedData, id)) {
                                const urlPath = filePathToUrl(filePath);
                                collectedData[lastParentId] = {
                                    path: pathToHeader.join('-->'),
                                    content: '',
                                    section: lastParentId,
                                    redirect: urlPath,
                                    tags,
                                    objectID: Buffer.from(urlPath + lastParentId).toString('base64'),
                                };
                            }
                        }

                        if (!id && isChild && !!el.nodeValue && !el?.parentNode?.getAttribute('id') && !el?.parentNode?.parentNode?.tagName.includes('SUP')) {
                            collectedData[lastParentId].content += el.nodeValue;
                            // eslint-disable-next-line no-param-reassign
                            el.parsed = true;
                        }
                        return true;
                    });
                    articlesIndex.saveObjects(Object.values(collectedData), {
                        autoGenerateObjectIDIfNotExist: false,
                    }).catch((e) => {
                        console.error(e);
                    });
                });
            }
        });
    });
});
