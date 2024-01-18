import { useCallback, useState } from 'react';
import {
  Badge,
  Banner,
  BlockStack,
  Button,
  ButtonGroup,
  Icon,
  IndexTable,
  InlineStack,
  LegacyCard,
  Page,
  Text,
  useIndexResourceState
} from '@shopify/polaris';
import API_ROUTES from '../constants/api';
import { useShop } from '../providers/ShopProvider';
import { useDeleteFilesMutation, useFilesQuery } from '../api';
import AppSpinner from '../components/AppSpinner';
import { CircleDownMajor, DeleteMinor } from '@shopify/polaris-icons';
import { useToast } from '../hooks/useToast';

export default function Dashboard() {
  const { info } = useShop();
  const [status, setStatus] = useState(true);
  const { showToast } = useToast();
  const [id, setId] = useState(null);
  const [files, setFiles] = useState([]);
  const { isFetching } = useFilesQuery({
    onSuccess: (data) => {
      if (!data.success) {
        setStatus(false);
        setFiles([]);
      } else {
        setStatus(true);
        setFiles(data.data);
      }
    }
  });

  const { isLoading, mutate } = useDeleteFilesMutation(id, {
    onSuccess: (data) => {
      if (!data.success) {
        showToast({
          error: true,
          message: data.message
        });
      } else {
        showToast({
          error: false,
          message: 'Success'
        });
      }
    },
    onError: (e) => {
      showToast({
        error: true,
        message: e
      });
    }
  });

  const handleRedirectOauth = useCallback(() => {
    if (!status) {
      window.open(
        `https://pluginpartner.smartifyapps.com/${API_ROUTES.AUTHORIZE}?shop=${info.name}`,
        '_self'
      );
    }
  }, [info.name, status]);

  const handleRemoveFile = useCallback(
    (id) => {
      setId(id);
      mutate(id);
    },
    [mutate]
  );

  const resourceName = {
    singular: 'file',
    plural: 'files'
  };

  const {
    selectedResources,
    allResourcesSelected,
    handleSelectionChange
  } = useIndexResourceState(files);

  const rowMarkup = files.map(({ id, name, type, url }) => (
    <IndexTable.Row
      id={id}
      key={id}
      selected={selectedResources.includes(id)}
    >
      <IndexTable.Cell>
        <Text variant="bodyMd" fontWeight="bold" as="span">
          {name}
        </Text>
      </IndexTable.Cell>
      <IndexTable.Cell>{type}</IndexTable.Cell>
      <IndexTable.Cell>
        <ButtonGroup>
          <Button url={url}>
            <Icon source={CircleDownMajor} />
          </Button>
          <Button
            tone="critical"
            onClick={() => {
              handleRemoveFile(id);
            }}
            loading={isLoading}
          >
            <Icon source={DeleteMinor} />
          </Button>
        </ButtonGroup>
      </IndexTable.Cell>
    </IndexTable.Row>
  ));

  if (isFetching) {
    return (
      <Page fullWidth>
        <AppSpinner />
      </Page>
    );
  }

  return (
    <Page fullWidth>
      <LegacyCard title="Welcome to App Partner">
        <LegacyCard.Section>
          <Text>Here we go!</Text>
        </LegacyCard.Section>

        <LegacyCard.Section></LegacyCard.Section>
      </LegacyCard>
      <LegacyCard>
        <LegacyCard.Section>
          <BlockStack
            align="space-between"
            inlineAlign="start"
            gap={400}
          >
            <InlineStack gap={200}>
              <Text>Integration Raw App</Text>
              <Badge tone={!status ? '' : 'success'}>
                {!status ? 'Inactive' : 'Active'}
              </Badge>
            </InlineStack>
            <Button
              onClick={handleRedirectOauth}
              disabled={status}
              external
            >
              {!status ? 'Connect' : 'Connected'}
            </Button>
          </BlockStack>
        </LegacyCard.Section>
      </LegacyCard>
      {!status && (
        <LegacyCard>
          <Banner tone="critical">
            <Text>
              Unauthorized. Please click button connect to access your
              file .
            </Text>
          </Banner>
        </LegacyCard>
      )}
      <LegacyCard>
        <IndexTable
          resourceName={resourceName}
          itemCount={files.length}
          selectedItemsCount={
            allResourcesSelected ? 'All' : selectedResources.length
          }
          onSelectionChange={handleSelectionChange}
          headings={[
            { title: 'Name' },
            { title: 'Type' },
            { title: 'Action' }
          ]}
          selectable={false}
        >
          {rowMarkup}
        </IndexTable>
      </LegacyCard>
    </Page>
  );
}
